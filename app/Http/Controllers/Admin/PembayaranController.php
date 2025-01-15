<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $debt = Debts::all();
        return view('admin.pembayaran.index', compact('rental', 'bank', 'totalseharusnya', 'total', 'debt'));
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
        return view('admin.pembayaran.index', compact( 'rental', 'bank', 'totalseharusnya', 'total', 'debt'));
    }

}
