<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\AccessoriesCategory;
use App\Models\Rental;
use App\Models\Bank;
use App\Models\Debts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PembayaranController extends Controller
{
    public function index()
    {
        $title = 'Yakin Menghapus Pembayarn?';
        $text = "Pembayaran Akan Dihapus Secara Permanen!";
        confirmDelete($title, $text);
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
        $currentYear = now()->year;
        $debt = Debts::whereYear('date_pay', $currentYear)->get();
        $hutang = $rental->sum('nominal_out');
        $uangmasuk = $debt->sum('pay_debts');
        return view('manager.pembayaran.index', compact('rental', 'bank', 'totalseharusnya', 'total', 'debt', 'hutang', 'uangmasuk'));
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

        // Format ulang input nominal_in dan pay_debts untuk menghapus simbol dan titik
        $nominal_in = str_replace(['Rp.', '.', ' '], '', $request->input('nominal_in'));
        $pay_debts = str_replace(['Rp.', '.', ' '], '', $request->input('pay_debts'));

        // Update nominal_in dan nominal_out di tabel rentals
        $rental = Rental::findOrFail($id);

        // Kurangi nominal_out dengan pay_debts yang baru
        $rental->nominal_out = $rental->nominal_out - $pay_debts;

        // Set nominal_in yang baru 
        $rental->nominal_in = $nominal_in;
        $rental->save();

        // Simpan data ke tabel debts
        $debts = Debts::create([
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
        $uangmasuk = $debt->sum('pay_debts');
        return view('manager.pembayaran.index', compact('rental', 'bank', 'totalseharusnya', 'total', 'debt', 'hutang', 'uangmasuk'));
    }
    public function update(Request $request, $id){
        $total_invoice = str_replace(['Rp.', '.', ' '], '', $request->input('total_invoice'));

        $rental = Rental::findOrFail($id);

        $rental->total_invoice = $total_invoice;
        $rental->save();
        return back()->withSuccess('Total Invoice Diperbarui.');

    }
    public function destroy(string $id)
    {
        $pembayaran = Debts::findOrFail($id); 
        $pembayaran->delete();
    
        return back()->with('success', 'Pembayaran Berhasil Dihapus');
    }
    public function destroyRental(string $id)
    {
        $pembayaran = Rental::findOrFail($id); 
        $pembayaran->delete();

        // Hapus semua AccessoriesCategory yang memiliki rental_id yang sama
        AccessoriesCategory::where('rental_id', $id)->delete();
        Debts::where('rental_id', $id)->delete();

        return back()->with('success', 'Pembayaran Berhasil Dihapus');
    }

    

}
