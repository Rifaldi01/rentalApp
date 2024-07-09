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
        $rental = Problem::leftjoin('rentals', 'problems.rental_id', '=', 'rentals.id')
            ->leftjoin('accessories_categories as a', 'a.rental_id', '=', 'rentals.id')
            ->leftjoin('accessories as b', 'a.accessories_id', '=', 'b.id')
            ->select(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po','rentals.date_start',
                'rentals.date_end', 'rentals.status', 'a.rental_id',
                \DB::raw('GROUP_CONCAT(b.name) as access'),
                'problems.id', 'problems.descript', 'problems.rental_id'
            )
            ->groupBy(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start',
                'rentals.date_end', 'rentals.status', 'a.rental_id', 'problems.id', 'problems.descript', 'problems.rental_id'
            )
            ->where('problems.status', 0)
            ->get();
//       return $rental;
        return view('admin.problem.index', compact('rental'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descript' => 'required',
        ]);
        Problem::create($request->all());
        $rental = Rental::find($request->input('rental_id'));
        $rental->status = 2;
        $rental->save();
        Alert::warning('Warning', 'Problem has been add');
        return redirect()->back();
    }

    public function destroy(Request $request, string $id)
    {
        $destroy = Problem::find($id);
        $destroy->status = 1;
        $destroy->save();

        $rental = Rental::findOrFail($destroy->rental_id);
        $rental->nominal_in = $request->input('nominal_in');
        $rental->nominal_out = $request->input('nominal_out');
        $rental->status = 0;
        $rental->save();

        $item = Item::findOrFail($rental->item_id);
        if ($item->status != 0) { // Only decrease stock if not already returned
            $item->status = 0;
            $item->save();

            // Restore accessory stock
            $accessoriesCategories = AccessoriesCategory::where('rental_id', $rental->id)->get();
            foreach ($accessoriesCategories as $accesCategory) {
                $accessory = Accessories::find($accesCategory->accessories_id);
                if ($accessory) {
                    $accessory->stok += $accesCategory->accessories_quantity;
                    $accessory->save();
                }
            }
        }

        Alert::success('Success', 'Problem has been finished');
        return back();
    }

    public function returned($id)
    {
        $destroy = Problem::find($id);
        $destroy->status = 0;
        $destroy->save();

        $rental = Rental::findOrFail($destroy->rental_id);
        if ($rental->status != 0) { // Only mark as returned if not already returned
            $rental->status = 0;
            $rental->save();

            $item = Item::findOrFail($rental->item_id);
            if ($item->status != 0) { // Only mark as returned if not already returned
                $item->status = 0;
                $item->save();

                // Restore accessory stock
                $accessoriesCategories = AccessoriesCategory::where('rental_id', $rental->id)->get();
                foreach ($accessoriesCategories as $accesCategory) {
                    $accessory = Accessories::find($accesCategory->accessories_id);
                    if ($accessory) {
                        $accessory->stok += $accesCategory->accessories_quantity;
                        $accessory->save();
                    }
                }
            }
        }

        Alert::success('Success', 'Item Returned');
        return back();
    }

}
