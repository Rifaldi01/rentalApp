<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use Carbon\Carbon;
use App\Models\Bank;
use App\Models\AccessoriesCategory;
use App\Models\Debts;
use App\Models\Rental;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Rental::with('cust')->withCount('debt')->withSum('debt', 'pay_debts');

        $year = $request->input('year', now()->year);
        $month = $request->input('month');
        $status = $request->input('status');
        $search = trim((string) $request->input('search'));

        if ($year !== 'all' && $year) {
            $query->whereYear('tgl_inv', $year);
        }
        if ($month && $month !== 'all') {
            $query->whereMonth('tgl_inv', $month);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('tgl_inv', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tgl_inv', '<=', $request->input('end_date'));
        }
        if ($status === 'paid') {
            $query->where('nominal_out', '<=', 0);
        } elseif ($status === 'unpaid') {
            $query->where('nominal_out', '>', 0);
        }
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('no_inv', 'like', "%{$search}%")
                    ->orWhere('no_po', 'like', "%{$search}%")
                    ->orWhereHas('cust', function ($customer) use ($search) {
                        $customer->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $rentals = $query->orderByDesc('tgl_inv')->orderByDesc('id')->get();
        $years = Rental::selectRaw('YEAR(tgl_inv) as year')
            ->whereNotNull('tgl_inv')->distinct()->orderByDesc('year')->pluck('year');
        if (!$years->contains((int) now()->year)) {
            $years->prepend(now()->year);
        }

        return view('admin.pembayaran.index', [
            'rentals' => $rentals,
            'years' => $years,
            'selectedYear' => $year,
            'selectedMonth' => $month,
            'selectedStatus' => $status,
            'search' => $search,
            'routePrefix' => 'admin',
        ]);
    }

    public function show($id)
    {
        $rentals = Rental::with(['cust', 'debt.bank'])
            ->findOrFail($id);

        $rawItemIds = json_decode($rentals->item_id, true);

        $itemIds = is_array($rawItemIds)
            ? $rawItemIds
            : (is_numeric($rentals->item_id) ? [$rentals->item_id] : []);

        $items = Item::withTrashed()
            ->whereIn('id', $itemIds)
            ->get();

        $accessories = AccessoriesCategory::with(['accessory'])
            ->where('rental_id', $rentals->id)
            ->get();

        $payments = $rentals->debt()
            ->with('bank')
            ->orderBy('installment_no')
            ->orderBy('date_pay')
            ->orderBy('id')
            ->get();

        $totalPaid = (int) $rentals->nominal_in
            + (int) $payments->sum('pay_debts');

        $remaining = max(0, (int) $rentals->nominal_out);

        $isPaid = $remaining <= 0;

        $nextInstallment = ((int) $payments->max('installment_no')) + 1;

        $bank = Bank::orderBy('name')->get();
        return view('admin.pembayaran.show', compact(
                'rentals',
                'items',
                'accessories',
                'payments',
                'totalPaid',
                'remaining',
                'isPaid',
                'nextInstallment',
                'bank'
            ) + [
                'routePrefix' => 'admin',
            ]);
    }

    public function bayar(Request $request, $id)
    {
        $cleanAmount = function ($value) {
            return (int) preg_replace('/[^0-9]/', '', (string) $value);
        };

        $request->validate([
            'pay_debts'  => 'required',
            'date_pay'   => 'required|date',
            'bank_id'    => 'nullable|exists:banks,id',
            'penerima'   => 'nullable|string|max:255',
            'description'=> 'nullable|string|max:1000',
        ]);

        $payment = $cleanAmount($request->input('pay_debts'));
        $diskon  = $cleanAmount($request->input('diskon', 0));
        $fee     = $cleanAmount($request->input('fee', 0));

        if ($payment <= 0) {
            return back()
                ->withErrors([
                    'pay_debts' => 'Nominal pembayaran harus lebih besar dari 0.'
                ])
                ->withInput();
        }

        DB::transaction(function () use (
            $id,
            $request,
            $payment,
            $diskon,
            $fee
        ) {
            // Kunci rental untuk mencegah pembayaran bersamaan.
            $rental = Rental::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            $remaining = (int) $rental->nominal_out;

            if ($payment > $remaining) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'pay_debts' => 'Nominal pembayaran melebihi sisa tagihan (' .
                        number_format($remaining, 0, ',', '.') . ').',
                ]);
            }

            // Nomor cicilan berikutnya.
            $nextInstallment = ((int) Debts::where('rental_id', $id)
                    ->max('installment_no')) + 1;

            // Tambahkan pembayaran ke total uang masuk.
            $rental->nominal_in = (int) $rental->nominal_in + $payment;

            // Kurangi sisa tagihan sesuai pembayaran.
            $rental->nominal_out = max(0, $remaining - $payment);

            // Hitung tambahan diskon saja.
            $diskonTambahan = max(
                0,
                $diskon - (int) $rental->diskon
            );

            // Kurangi sisa tagihan jika ada tambahan diskon.
            if ($diskonTambahan > 0) {
                $rental->nominal_out = max(
                    0,
                    (int) $rental->nominal_out - $diskonTambahan
                );
            }

            $rental->diskon = $diskon;
            $rental->fee = $fee;

            $rental->save();

            // Simpan histori cicilan.
            Debts::create([
                'rental_id'      => $id,
                'installment_no' => $nextInstallment,
                'bank_id'        => $request->input('bank_id') ?: null,
                'pay_debts'      => $payment,
                'penerima'       => $request->input('penerima'),
                'date_pay'       => $request->input('date_pay'),
                'description'    => $request->input('description'),
            ]);
        });

        return redirect()
            ->route('admin.pembayaran.show', $id)
            ->withSuccess('Pembayaran berhasil disimpan.');
    }

    public function filter(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        return redirect()->route('admin.pembayaran.index', $request->only('start_date', 'end_date'));
    }

    public function update(Request $request, $id)
    {
        $total_invoice = str_replace(['Rp.', '.', ' '], '', $request->input('total_invoice'));

        $rental = Rental::findOrFail($id);

        $rental->total_invoice = $total_invoice;
        $rental->save();
        return back()->withSuccess('Total Invoice Diperbarui.');

    }

    public function finis($id)
    {
        // Temukan objek rental berdasarkan ID
        $rental = Rental::findOrFail($id);
        $rental->nominal_out = 0;
        $rental->save();

        return redirect()->back()->with('success', 'Rental Finished');
    }
    public function destroyPayment($rentalId, $paymentId)
    {
        DB::transaction(function () use ($rentalId, $paymentId) {

            // Kunci data rental agar tidak bentrok dengan pembayaran bersamaan.
            $rental = Rental::whereKey($rentalId)
                ->lockForUpdate()
                ->firstOrFail();

            // Pastikan cicilan memang milik rental tersebut.
            $payment = Debts::where('id', $paymentId)
                ->where('rental_id', $rental->id)
                ->lockForUpdate()
                ->firstOrFail();

            $nominalDihapus = (int) $payment->pay_debts;

            // Hapus histori cicilan.
            $payment->delete();

            // Kembalikan nominal cicilan ke sisa tagihan.
            $rental->nominal_out = (int) $rental->nominal_out + $nominalDihapus;
            $rental->save();

            // Susun ulang nomor cicilan agar tetap berurutan.
            $payments = Debts::where('rental_id', $rental->id)
                ->orderBy('date_pay')
                ->orderBy('id')
                ->get();

            foreach ($payments as $index => $row) {
                $row->installment_no = $index + 1;
                $row->save();
            }
        });

        $prefix = request()->routeIs('manager.*') ? 'manager' : 'admin';

        return redirect()
            ->route($prefix . '.pembayaran.show', $rentalId)
            ->with('success', 'Cicilan berhasil dihapus dan nominalnya dikembalikan ke sisa tagihan.');
    }
    public function printInvoice($id)
    {
        $rentals = Rental::with('cust')->findOrFail($id);

        // Ambil item rental.
        $rawItemIds = json_decode($rentals->item_id, true);

        $itemIds = is_array($rawItemIds)
            ? $rawItemIds
            : (is_numeric($rentals->item_id) ? [$rentals->item_id] : []);

        $items = \App\Models\Item::withTrashed()
            ->whereIn('id', $itemIds)
            ->get();

        // Ambil accessories rental.
        $accessories = AccessoriesCategory::with('accessory')
            ->where('rental_id', $rentals->id)
            ->get();

        // Ambil histori pembayaran.
        $payments = $rentals->debt()
            ->with('bank')
            ->orderBy('date_pay')
            ->orderBy('id')
            ->get();

        // nominal_in adalah total uang masuk yang tersimpan pada rental.
        $totalPaid = (int) $rentals->nominal_in;
        $remaining = max(0, (int) $rentals->nominal_out);
        $isPaid = $remaining <= 0;

        return view('admin.pembayaran.invoice', compact(
            'rentals',
            'items',
            'accessories',
            'payments',
            'totalPaid',
            'remaining',
            'isPaid'
        ));
    }
}
