<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Problem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportProblemController extends Controller
{
    public function index()
    {
        $report = Problem::leftJoin('rentals', 'problems.rental_id', '=', 'rentals.id')
            ->leftJoin('accessories_categories as a', 'a.rental_id', '=', 'rentals.id')
            ->leftJoin('accessories as b', 'a.accessories_id', '=', 'b.id')
            ->select(
                'rentals.id',
                'rentals.customer_id',
                'rentals.item_id',
                'rentals.name_company',
                'rentals.addres_company',
                'rentals.phone_company',
                'rentals.no_po',
                'rentals.no_inv',
                'rentals.date_start',
                'rentals.date_end',
                'rentals.status',
                'rentals.created_at',
                'a.rental_id',

                'problems.created_at',

                \DB::raw('GROUP_CONCAT(b.name) as access'),

                'problems.id as problem_id',
                'problems.rental_id',
                'problems.status',
                'problems.descript'
            )
            ->where('problems.status', 0)
            ->groupBy(
                'rentals.id',
                'rentals.customer_id',
                'rentals.item_id',
                'rentals.name_company',
                'rentals.addres_company',
                'rentals.phone_company',
                'rentals.no_po',
                'rentals.no_inv',
                'rentals.date_start',
                'rentals.date_end',
                'rentals.status',
                'rentals.created_at',
                'a.rental_id',
                'problems.created_at',
                'problems.id',
                'problems.rental_id',
                'problems.status',
                'problems.descript'
            )
            ->orderBy('rentals.created_at', 'desc')
            ->get();

        return view('admin.reportproblem.index', compact('report'));
    }

    public function filter(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|before_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date|before_or_equal:today',
        ], [
            'end_date.after_or_equal'   => 'Tanggal Akhir Tidak Boleh Kurang Dari Tanggal Mulai',
            'start_date.before_or_equal'=> 'Tanggal Mulai Harus Tanggal Sebelum Atau Sama Dengan Hari Ini',
            'end_date.before_or_equal'  => 'Tanggal Akhir Harus Tanggal Sebelum Atau Sama Dengan Hari Ini',
        ]);

        $start_date = Carbon::parse($request->start_date)->startOfDay();
        $end_date   = Carbon::parse($request->end_date)->endOfDay();

        $report = Problem::leftJoin('rentals', 'problems.rental_id', '=', 'rentals.id')
            ->leftJoin('accessories_categories as a', 'a.rental_id', '=', 'rentals.id')
            ->leftJoin('accessories as b', 'a.accessories_id', '=', 'b.id')
            ->select(
                'rentals.id',
                'rentals.customer_id',
                'rentals.item_id',
                'rentals.name_company',
                'rentals.addres_company',
                'rentals.phone_company',
                'rentals.no_po',
                'rentals.no_inv',
                'rentals.date_start',
                'rentals.date_end',
                'rentals.status',
                'rentals.created_at',
                'a.rental_id',

                'problems.created_at',

                \DB::raw('GROUP_CONCAT(b.name) as access'),

                'problems.id as problem_id',
                'problems.rental_id',
                'problems.status',
                'problems.descript'
            )
            ->where('problems.status', 0)
            ->whereBetween('rentals.date_start', [$start_date, $end_date])
            ->groupBy(
                'rentals.id',
                'rentals.customer_id',
                'rentals.item_id',
                'rentals.name_company',
                'rentals.addres_company',
                'rentals.phone_company',
                'rentals.no_po',
                'rentals.no_inv',
                'rentals.date_start',
                'rentals.date_end',
                'rentals.status',
                'rentals.created_at',
                'a.rental_id',
                'problems.created_at',
                'problems.id',
                'problems.rental_id',
                'problems.status',
                'problems.descript'
            )
            ->orderBy('rentals.created_at', 'desc')
            ->get();

        return view('admin.reportproblem.index', compact('report'));
    }
}
