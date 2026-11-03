<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accessories;
use App\Models\AccessoriesCategory;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AccessoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $acces = Accessories::all();
        $accessoriesData = [];

        foreach ($acces as $accessory) {
            $stok = $accessory->stok;

            // Jumlah accessories yang sedang disewa (status_acces = 1)
            $rentedQty = AccessoriesCategory::where('accessories_id', $accessory->id)
                ->where('status_acces', 1)
                ->sum('accessories_quantity');

            // Jumlah accessories yang sedang maintenance (status = 0)
            $maintenanceQty = \DB::table('maintenance_accessories')
                ->where('accessories_id', $accessory->id)
                ->where('status', 0)
                ->sum('qty');

            // Jumlah accessories yang sedang dipinjam oleh divisi (status = 1)
            $borrowedQty = \DB::table('rental_divisi_details')
                ->where('accessories_id', $accessory->id)
                ->where('status', 0)
                ->sum('qty');

            // Hitung total stok (stok tersedia + yang disewa + yang maintenance + yang dipinjam)
            $stokAll = $stok + $rentedQty + $maintenanceQty + $borrowedQty;

            $accessoriesData[] = [
                'id' => $accessory->id,
                'name' => $accessory->name,
                'stok_all' => $accessory->stok_all, // gunakan hasil perhitungan stok total
                'stok' => $stok,
                'rentedQty' => $rentedQty,
                'maintenanceQty' => $maintenanceQty,
                'borrowedQty' => $borrowedQty,
            ];
        }

        // Ambil data rental accessories yang sedang aktif
        $rentals = AccessoriesCategory::where('status_acces', 1)
            ->with('accessory', 'rental.cust')
            ->get();
        // Kirim data ke view
        return view('admin.accessories.index', compact(['accessoriesData', 'rentals']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'stok' => 'required|numeric',
        ]);
        Accessories::create($request->all());
        Alert::success('Success', 'Add Accessories Success');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Accessories::whereId($id)->delete();
        alert()->success('Succes','Customer Deleted Successfully');
        return back();
    }
    public function sale()
    {
        $accesSale = BarangKeluar::with('accessories')->get();
        $accessories = Accessories::all();
        return view('admin.accessories.accesSale', compact('accesSale', 'accessories'));
    }
}
