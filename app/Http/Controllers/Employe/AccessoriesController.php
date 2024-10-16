<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Models\Accessories;
use App\Models\AccessoriesCategory;
use Illuminate\Http\Request;

class AccessoriesController extends Controller
{
    public function index()
    {
        $cust = Accessories::latest()->paginate();
        $title = 'Delete Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $acces = Accessories::all();

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
            'stok' => $stok,
            'rentedQty' => $rentedQty,
            'stokAll' => $stokAll
        ];
    }
        return view('employe.accessories.index', compact('accessoriesData'));
    }
}
