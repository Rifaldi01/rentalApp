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

        return view('employe.accessories.index', compact('accessoriesData', 'rentals'));
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
    public function tambah(Request $request, string $id)
    {
        // ✅ Validasi input
        $request->validate([
            'stok' => 'required|numeric|min:1',
        ]);

        // ✅ Ambil data accessories
        $accessory = Accessories::findOrFail($id);

        // ✅ Tambahkan stok dan stok_all
        $accessory->stok += $request->stok;
        $accessory->stok_all += $request->stok;

        // ✅ Simpan perubahan
        $accessory->save();

        Alert::success('Success', 'Stok Accessories berhasil diperbarui');
        return back();
    }

}
