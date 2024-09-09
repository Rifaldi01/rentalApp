<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportServiceController extends Controller
{
    public function index()
    {
        $report = Service::all();
        $totalincome = $report->sum(function($item) {
            return $item->nominal_in - $item->diskon - $item->ongkir;
        });
        $totaldiskon = $report->sum('diskon');
        $totalbiaya= $report->sum('biaya_ganti');
        $totalin = $report->sum('nominal_in');
        $totalongkir = $report->sum('ongkir');
        $totaloutside = $report->sum('nominal_out');
        return view('manager.reportservice.index', compact('totalbiaya','totalin','report', 'totalincome', 'totaloutside', 'totaldiskon', 'totalongkir'));
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
        $start_date = Carbon::parse(request()->start_date)->toDateTimeString();
        $end_date = Carbon::parse(request()->end_date)->toDateTimeString();
        $report = Service::whereBetween('created_at',[$start_date,$end_date])->get();
        $totalincome = $report->sum(function($item) {
            return $item->nominal_in - $item->diskon - $item->ongkir;
        });
        $totaldiskon = $report->sum('diskon');
        $totalbiaya= $report->sum('biaya_ganti');
        $totalin = $report->sum('nominal_in');
        $totalongkir = $report->sum('ongkir');
        $totaloutside = $report->sum('nominal_out');
        return view('manager.reportservice.index', compact('totalbiaya','totalin','report', 'totalincome', 'totaloutside', 'totaldiskon', 'totalongkir'));
    }
}
