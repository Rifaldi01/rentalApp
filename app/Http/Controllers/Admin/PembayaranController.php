<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Debts;
use App\Models\Rental;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PembayaranController extends Controller
{
    public function index()
    {
        $rental = Rental::where('nominal_out', '!=', '0')->get();
        $bank = Bank::all();
        $totalseharusnya = $rental->groupBy('id')->map(function ($group) {
            return $group->sum(function ($item){
                return $item->nominal_in + $item->nominal_out;
            });
        });
        $total = $rental->groupBy('id')->map(function ($group) {
            return $group->sum(function ($item){
                return $item->total_invoice - $item->diskon + $item->ppn;
            });
        });
        $currentYear = now()->year;
        $debt = Debts::whereYear('date_pay', $currentYear)->get();
        $hutang = $rental->sum('nominal_out');
        $diskon = $debt->sum(function ($item) {
            return $item->rental->diskon;
        });
        $sisabayar = $debt->sum(function ($item) {
            return $item->rental->nominal_out;
        });
        $totalbersih = $debt->sum(function ($item) {
            return $item->pay_debts - $item->rental->diskon;
        });
        $totals = $debt->groupBy('id')->map(function ($group) {
            return $group->sum(function ($item){
                return $item->pay_debts - $item->rental->diskon;
            });
        });
        $uangmasuk = $debt->sum('pay_debts');
        return view('admin.pembayaran.index', compact([
            'totalbersih',
            'sisabayar',
            'diskon',
            'rental',
            'bank',
            'totalseharusnya',
            'total',
            'debt',
            'hutang',
            'uangmasuk',
            'totals',
        ]));
    }
    public function bayar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nominal_in'   => 'required',
            'pay_debts'    => 'required',
            'date_pay'     => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Format ulang input nominal_in, pay_debts, dan diskon untuk menghapus simbol dan titik
        $nominal_in = str_replace(['Rp.', '.', ' '], '', $request->input('nominal_in'));
        $pay_debts = str_replace(['Rp.', '.', ' '], '', $request->input('pay_debts'));
        $diskon = str_replace(['Rp.', '.', ' '], '', $request->input('diskon'));

        // Ambil data rental
        $rental = Rental::findOrFail($id);

        // Kurangi nominal_out dengan pay_debts
        $new_nominal_out = $rental->nominal_out - $pay_debts;

        // Kurangi nominal_out dengan diskon jika berbeda dari sebelumnya
        if ($diskon != $rental->diskon) {
            $new_nominal_out -= $diskon;
        }

        // Pastikan nominal_out tidak menjadi negatif
        $rental->nominal_out = max(0, $new_nominal_out);

        // Set nominal_in dan diskon yang baru
        $rental->nominal_in = $nominal_in;
        $rental->diskon = $diskon;
        $rental->save();

        // Simpan data ke tabel debts
        Debts::create([
            'rental_id'  => $id,
            'bank_id'    => $request->input('bank_id'),
            'pay_debts'  => $pay_debts,
            'penerima'   => $request->input('penerima'),
            'date_pay'   => $request->input('date_pay'),
            'description'=> $request->input('description'),
        ]);

        return back()->withSuccess('Pembayaran Berhasil');
    }


    public function filter(Request $request){
        $request->validate([
            'start_date' => 'required|date|before_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:today',
        ], [
            'end_date.after_or_equal' => 'Tanggal Akhir Tidak Boleh Kurang Dari Tanggal Mulai',
            'start_date.before_or_equal' => 'Tanggal Mulai Harus Tanggal Sebelum Atau Sama Dengan Hari Ini',
            'end_date.before_or_equal' => 'Tanggal Akhir Harus Tanggal Sebelum Atau Sama Dengan Hari Ini',
        ]);

        // Parse the dates
        $start_date = Carbon::parse($request->start_date)->toDateTimeString();
        $end_date = Carbon::parse($request->end_date)->toDateTimeString();

        // Query the rentals with filters
        $debt = Debts::with('rental', 'bank')
        ->whereBetween('date_pay', [$start_date, $end_date])
        ->orderBy('date_pay', 'asc')
        ->get();;

        // Calculate totals
        $rental = Rental::where('nominal_out', '!=', '0')->get();
        $bank = Bank::all();
        $totalseharusnya = $rental->groupBy('id')->map(function ($group) {
            return $group->sum(function ($item){
                return $item->nominal_in + $item->nominal_out;
            });
        });
        $total = $rental->groupBy('id')->map(function ($group) {
            return $group->sum(function ($item){
                return $item->nominal_in + $item->nominal_out - $item->diskon;
            });
        });
        $hutang = $rental->sum('nominal_out');
        $diskon = $debt->sum(function ($item) {
            return $item->rental->diskon;
        });
        $sisabayar = $debt->sum(function ($item) {
            return $item->rental->nominal_out;
        });
        $totalbersih = $debt->sum(function ($item) {
            return $item->pay_debts - $item->rental->diskon;
        });
        $totals = $debt->groupBy('id')->map(function ($group) {
            return $group->sum(function ($item){
                return $item->pay_debts - $item->rental->diskon;
            });
        });
        $uangmasuk = $debt->sum('pay_debts');
        return view('admin.pembayaran.index', compact([
            'totalbersih',
            'sisabayar',
            'diskon',
            'rental',
            'bank',
            'totalseharusnya',
            'total',
            'debt',
            'hutang',
            'uangmasuk',
            'totals',
        ]));
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

}
