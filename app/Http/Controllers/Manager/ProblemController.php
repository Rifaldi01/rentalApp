<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Problem;
use Illuminate\Http\Request;

class ProblemController extends Controller
{
    public function index()
    {
        $problem = Problem::latest()->paginate();
        $title = 'Problem Finished';
        $text = "Are you sure you Problem Finished";
        confirmDelete($title, $text);
        $rental = Problem::leftjoin('rentals', 'problems.rental_id', '=', 'rentals.id')
            ->leftjoin('accessories_categories as a', 'a.rental_id', '=', 'rentals.id')
            ->leftjoin('accessories as b', 'a.accessories_id', '=', 'b.id')
            ->select(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po','rentals.date_start',
                'rentals.date_end', 'rentals.status', 'a.rental_id',
                \DB::raw('GROUP_CONCAT(b.name) as access'),
                'problems.id', 'problems.descript'
            )
            ->groupBy(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start',
                'rentals.date_end', 'rentals.status', 'a.rental_id', 'problems.id', 'problems.descript'
            )
            ->where('problems.status', 0)
            ->get();
//        return $rental;
        return view('superadmin.problem.index', compact('rental'));
    }

}
