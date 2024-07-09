<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportMaintenController extends Controller
{
    public function index()
    {
        $report = Maintenance::with('item.cat')->get();
        return view('superadmin.reportmainten.index', compact('report'));
    }
    public function filter(Request $request){
        $start_date = Carbon::parse(request()->start_date)->toDateTimeString();
        $end_date = Carbon::parse(request()->end_date)->toDateTimeString();
        $report = Maintenance::whereBetween('created_at',[$start_date,$end_date])->get();
        return view('superadmin.reportmainten.index', compact('report'));
    }
}
