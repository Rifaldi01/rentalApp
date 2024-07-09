<?php

namespace App\Http\Controllers\SuperAdmin;

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
        $totalongkir = $report->sum('ongkir');
        $totaloutside = $report->sum('nominal_out');
        return view('superadmin.reportservice.index', compact('report', 'totalincome', 'totaloutside', 'totaldiskon', 'totalongkir'));
    }
    public function filter(Request $request){
        $start_date = Carbon::parse(request()->start_date)->toDateTimeString();
        $end_date = Carbon::parse(request()->end_date)->toDateTimeString();
        $report = Service::whereBetween('created_at',[$start_date,$end_date])->get();
        $totalincome = $report->sum(function($item) {
            return $item->nominal_in - $item->diskon - $item->ongkir;
        });
        $totaldiskon = $report->sum('diskon');
        $totalongkir = $report->sum('ongkir');
        $totaloutside = $report->sum('nominal_out');
        return view('superadmin.reportservice.index', compact('report', 'totalincome', 'totaloutside', 'totaldiskon', 'totalongkir'));
    }
}
