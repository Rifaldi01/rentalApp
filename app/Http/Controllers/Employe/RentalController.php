<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Models\Accessories;
use App\Models\AccessoriesCategory;
use App\Models\Item;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    public function index(){
        $rentals = Rental::all();
        return view('employe.rental.index',  compact('rentals'));
    }
    public function approveRental($id)
    {
        DB::beginTransaction();

        try {
            $rental = Rental::findOrFail($id);

            if ($rental->status != 3) {
                return redirect()->back()->withErrors(['message' => 'Rental ini sudah diproses atau tidak valid untuk approval.']);
            }

            // Update status rental menjadi disetujui gudang
            $rental->status = 1; // 4 = Disetujui Gudang
            $rental->save();

            DB::commit();
            return redirect()->route('employe.rental')->withSuccess('Rental berhasil disetujui oleh gudang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['message' => 'Terjadi kesalahan saat menyetujui rental: ' . $e->getMessage()]);
        }
    }

    /**
     * Menolak rental oleh gudang (jika tidak disetujui).
     */
    public function reject($id)
    {
        $rental = Rental::findOrFail($id);
        if ($rental->status != 3) {
            return redirect()->back()->withErrors(['message' => 'Rental ini sudah diproses atau tidak valid untuk ditolak.']);
        }

        $rental->status = 5; // 5 = Ditolak Gudang
        $rental->approved_by = auth()->id();
        $rental->approved_at = now();
        $rental->save();

        return redirect()->route('admin.rental.approval.index')->withSuccess('Rental telah ditolak oleh gudang.');
    }
    public function kembali($id){
        $rental = Rental::findOrFail($id);
        $rental->status = 0;
        $rental->save();
        return back()->with('success', 'Rental telah dikembalikan.');
    }

}
