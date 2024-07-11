<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(){
        $report = Rental::leftjoin('accessories_categories as a', 'a.rental_id', '=', 'rentals.id')
            ->leftjoin('accessories as b', 'a.accessories_id', '=', 'b.id')
            ->select(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po','rentals.date_start',
                'rentals.date_end', 'rentals.status', 'nominal_in', 'nominal_out', 'diskon', 'ongkir', 'a.rental_id',
                \DB::raw('GROUP_CONCAT(b.name) as access'),
                \DB::raw('SUM(nominal_in - diskon - ongkir) as total'),
            )
            ->groupBy(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start',
                'rentals.date_end', 'rentals.status', 'nominal_in', 'nominal_out', 'diskon', 'ongkir', 'a.rental_id'
            )
            ->get();
        $totaldiskon = $report->sum('diskon');
        $totalongkir = $report->sum('ongkir');
        $totalincome = $report->sum(function($item) {
            return $item->nominal_in - $item->diskon - $item->ongkir;
        });
        $totaloutside = $report->sum('nominal_out');
        return view('manager.report.index', compact('report', 'totaldiskon', 'totalongkir', 'totalincome', 'totaloutside'));
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

        $report = Rental::leftjoin('accessories_categories as a', 'a.rental_id', '=', 'rentals.id')
            ->leftjoin('accessories as b', 'a.accessories_id', '=', 'b.id')
            ->select(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po','rentals.date_start',
                'rentals.date_end', 'rentals.status', 'nominal_in', 'nominal_out', 'diskon', 'a.rental_id','ongkir',
                \DB::raw('GROUP_CONCAT(b.name) as access'),
                \DB::raw('SUM(nominal_in - diskon - ongkir) as total'),
            )
            ->groupBy(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start',
                'rentals.date_end', 'rentals.status', 'nominal_in', 'nominal_out', 'diskon', 'a.rental_id', 'ongkir',
            )
            ->whereBetween('rentals.created_at', [$start_date, $end_date])
            ->get();
        $totaldiskon = $report->sum('diskon');
        $totalongkir = $report->sum('ongkir');
        $totalincome = $report->sum(function($item) {
            return $item->nominal_in - $item->diskon - $item->ongkir;
        });
        $totaloutside = $report->sum('nominal_out');
        return view('manager.report.index', compact('report', 'totaldiskon', 'totalongkir', 'totalincome', 'totaloutside'));
    }
}
