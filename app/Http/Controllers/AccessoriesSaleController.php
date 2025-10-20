<?php

namespace App\Http\Controllers;

use App\Models\Accessories;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccessoriesSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accesSale = BarangKeluar::with('accessories')->get();
        $accessories = Accessories::all();
        return view('employe.accessories.accesSale', compact('accesSale', 'accessories'));
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
        // === Validasi input ===
        $request->validate([
            'accessories_id' => 'required|array',
            'accessories_id.*' => 'exists:accessories,id',
            'qty' => 'required|array',
            'qty.*' => 'numeric|min:1',
            'description' => 'required|array',
            'description.*' => 'string',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->accessories_id as $index => $accId) {

                // 1️⃣ Simpan data ke tabel barang_keluar
                BarangKeluar::create([
                    'accessories_id' => $accId,
                    'qty' => $request->qty[$index],
                    'description' => $request->description[$index],
                ]);

                // 2️⃣ Kurangi stok dan stok_all pada tabel accessories
                $accessory = Accessories::find($accId);

                if ($accessory) {
                    $qty = (int) $request->qty[$index];

                    // Pastikan tidak negatif
                    $accessory->stok = max(0, $accessory->stok - $qty);
                    $accessory->stok_all = max(0, $accessory->stok_all - $qty);

                    $accessory->save();
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Data barang keluar berhasil disimpan dan stok diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
