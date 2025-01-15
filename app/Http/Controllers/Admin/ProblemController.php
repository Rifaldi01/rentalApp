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
                'rentals.date_end', 'rentals.status', 'rentals.no_inv', 'rentals.image', 'a.rental_id',
                \DB::raw('GROUP_CONCAT(b.name) as access'),
                'problems.id', 'problems.descript', 'problems.rental_id'
            )
            ->groupBy(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start',
                'rentals.date_end', 'rentals.status', 'a.rental_id', 'problems.id', 'rentals.no_inv', 'rentals.image', 'problems.descript', 'problems.rental_id'
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
        $rental->status = 0;
        $rental->save();

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
            $rental->status = 2;
            $rental->save();

            $itemIds = json_decode($rental->item_id, true) ?? []; // Jika item_id disimpan dalam format JSON

            // Update status item menjadi 0 jika tidak 3
            foreach ($itemIds as $itemId) {
                $item = Item::find($itemId);
                if ($item) {
                    if ($item->status != 3 && $item->status != 1) { // Cek jika status bukan 3 atau 1
                        $item->status = 0; // Ubah status menjadi 0
                        $item->save();
                    }
                }
            }

            // Update status aksesori dan kembalikan stok
            $accessoriesCategories = AccessoriesCategory::where('rental_id', $rental->id)->get();
            foreach ($accessoriesCategories as $accessoriesCategory) {
                // Update status_acces di tabel pivot
                AccessoriesCategory::where('rental_id', $rental->id)
                    ->where('accessories_id', $accessoriesCategory->accessories_id)
                    ->update(['status_acces' => 0]);

                // Kembalikan stok aksesori
                $accessory = Accessories::find($accessoriesCategory->accessories_id);
                if ($accessory) {
                    $accessory->stok += $accessoriesCategory->accessories_quantity;
                    $accessory->save();
                }
            }
        }

        Alert::success('Success', 'Item Returned');
        return back();
    }

}
