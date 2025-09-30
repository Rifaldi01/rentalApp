<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Models\Accessories;
use App\Models\AccessoriesCategory;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AccessoriesController extends Controller
{
    public function index()
    {
        $cust = Accessories::latest()->paginate();
        $title = 'Delete Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $acces = Accessories::where('stok', '>', 0)->get();

        // Buat array untuk menyimpan data stok dan quantity per accessories
        $accessoriesData = [];

        // Loop melalui setiap accessories
        foreach ($acces as $accessory) {
            // Stok awal
            $stok = $accessory->stok;

            // Total quantity yang sedang dirental untuk accessories ini
            $rentedQty = AccessoriesCategory::where('accessories_id', $accessory->id)
                ->where('status_acces', 1)
                ->sum('accessories_quantity');

            // Total stok + quantity yang sedang dirental
            $stokAll = $stok + $rentedQty;

            // Simpan data ke dalam array
            $accessoriesData[] = [
                'id' => $accessory->id,
                'name' => $accessory->name,
                'stok_all' => $accessory->stok_all,
                'stok' => $stok,
                'rentedQty' => $rentedQty,
                'stokAll' => $stokAll
            ];
        }
        $rental = AccessoriesCategory::where('status_acces', 1)->with(['accessory', 'rental.cust'])->get();
        // Kirim data ke view
        return view('employe.accessories.index', compact(['accessoriesData', 'rental']));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'stok' => 'required|numeric',
            'stok_all' => 'required|numeric',
        ]);
        Accessories::create($request->all());
        Alert::success('Success', 'Add Accessories Success');
        return back();
    }
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);
        $post = Accessories::find($id);
        $post->update($request->all());
        Alert::success('Success', 'Edit Accessories Success');
        return back();
    }

}
