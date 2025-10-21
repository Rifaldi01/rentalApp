<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accessories;
use App\Models\AccessoriesCategory;
use App\Models\Item;
use App\Models\Problem;
use App\Models\Rental;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ProblemController extends Controller
{
    public function index()
    {
        $problem = Problem::latest()->paginate();
        $title = 'Problem Finished';
        $text = "Are you sure you Problem Finished";
        confirmDelete($title, $text);
        $rentals = Problem::leftjoin('rentals', 'problems.rental_id', '=', 'rentals.id')
            ->leftjoin('accessories_categories as a', 'a.rental_id', '=', 'rentals.id')
            ->leftjoin('accessories as b', 'a.accessories_id', '=', 'b.id')
            ->select(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po','rentals.date_start',
                'rentals.date_end', 'rentals.status', 'rentals.no_inv', 'rentals.image', 'a.rental_id', 'rentals.nominal_in', 'rentals.nominal_out', 'rentals.diskon', 'rentals.total_invoice',
                \DB::raw('GROUP_CONCAT(b.name) as access'),
                'problems.id', 'problems.descript', 'problems.rental_id', 'problems.status'
            )
            ->groupBy(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start',
                'rentals.date_end', 'rentals.status', 'a.rental_id', 'problems.id', 'rentals.no_inv', 'rentals.image',
                'problems.descript', 'problems.rental_id', 'rentals.nominal_in', 'rentals.nominal_out', 'rentals.diskon', 'rentals.total_invoice', 'problems.status'
            )
            ->where('problems.status', '!=', 1)
            ->get();
//       return $rental;
        return view('admin.problem.index', compact('rentals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descript' => 'required',
        ]);
        Problem::create($request->all());
        $rental = Rental::find($request->input('rental_id'));
        $rental->status = 4;
        $rental->save();
        Alert::warning('Warning', 'Problem has been add');
        return redirect()->back();
    }

    public function destroy(Request $request, string $id)
    {
        $destroy = Problem::find($id);
        $destroy->status = 1;
        $destroy->save();

        Alert::success('Success', 'Problem has been finished');
        return back();
    }

    public function returned(Request $request, $id)
    {
        $destroy = Problem::find($id);
        $destroy->descript = $request->input('descript');
        $destroy->save();

        Alert::success('Success', 'Item Returned');
        return back();
    }

}
