<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Models\Accessories;
use App\Models\MaintenanceAccessories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccessoriesMaintenace extends Controller
{
    public function index(){
        $mainten = MaintenanceAccessories::with('accessories')->get();
        $accessories = Accessories::all();
        return view('employe.maintenance.accessories', compact('mainten', 'accessories'));
    }
    public function accesStore(Request $request){
        // === Validasi input ===
        // ✅ Validasi form
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

                // 1️⃣ Simpan data ke maintenance_accessories
                MaintenanceAccessories::create([
                    'accessories_id' => $accId,
                    'qty' => $request->qty[$index],
                    'description' => $request->description[$index],
                ]);

                // 2️⃣ Kurangi stok pada tabel accessories
                $accessory = Accessories::find($accId);

                if ($accessory) {
                    $accessory->stok = max(0, $accessory->stok - $request->qty[$index]); // pastikan stok tidak negatif
                    $accessory->save();
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Data maintenance accessories berhasil disimpan dan stok diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }
    public function finis($id)
    {
        DB::beginTransaction();
        try {
            $maintenance = MaintenanceAccessories::findOrFail($id);

            // Cegah double restore
            if ($maintenance->status == 1) {
                return redirect()->back()->with('info', 'Data ini sudah diselesaikan sebelumnya.');
            }

            $accessory = Accessories::find($maintenance->accessories_id);

            if ($accessory) {
                // Kembalikan stok
                $accessory->stok += $maintenance->qty;
                $accessory->save();
            }

            // Ubah status jadi selesai
            $maintenance->status = 1;
            $maintenance->save();

            DB::commit();

            return redirect()->back()->with('success', 'Maintenance selesai. Stok accessories telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyelesaikan maintenance: ' . $e->getMessage());
        }
    }
}
