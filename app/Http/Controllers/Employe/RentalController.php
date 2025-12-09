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
    public function index()
    {
        $rentals = Rental::with('problems')->get();
        return view('employe.rental.index', compact('rentals'));
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
    public function finis($id)
    {
        $rental = Rental::findOrFail($id);
        $rental->status = 0;
        $rental->save();

        return back()->withSuccess('Barang Telah Kembali Semua');
    }
    public function kembali(Request $request, $id)
    {
        // ✅ 1. Proses Item (ubah status)
        if ($request->has('items')) {
            foreach ($request->items as $data) {
                $item = Item::find($data['id']);
                if ($item && isset($data['status'])) {
                    $item->status = $data['status'];
                    $item->save();
                }
            }
        }

        // ✅ 2. Proses Accessories (kembalikan stok dan ubah status)
        if ($request->has('accessories')) {
            foreach ($request->accessories as $data) {
                $jumlahKembali = (int)($data['kembali'] ?? 0);
                if ($jumlahKembali <= 0) continue;

                $accesCat = AccessoriesCategory::find($data['id']);
                if (!$accesCat) continue;

                // Update jumlah kembali dan quantity
                $accesCat->kembali = ($accesCat->kembali ?? 0) + $jumlahKembali;
                $accesCat->accessories_quantity = max(0, $accesCat->accessories_quantity - $jumlahKembali);

                // ✅ Jika accessories_quantity sudah 0, ubah status_acces menjadi 0 (selesai)
                if ($accesCat->accessories_quantity == 0) {
                    $accesCat->status_acces = 0;
                }

                $accesCat->save();

                // Tambah stok ke tabel accessories
                $acces = Accessories::find($accesCat->accessories_id);
                if ($acces) {
                    $acces->stok = ($acces->stok ?? 0) + $jumlahKembali;
                    $acces->save();
                }
            }
        }

        return back()->with('success', 'Data pengembalian berhasil diperbarui.');
    }
    public function hsty()
    {

        $rentals = Rental::leftjoin('accessories_categories as a', 'a.rental_id', '=', 'rentals.id')
            ->leftjoin('accessories as b', 'a.accessories_id', '=', 'b.id')
            ->select(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start', 'date_pays',
                'rentals.date_end', 'rentals.status', 'a.rental_id', 'nominal_in', 'nominal_out', 'diskon', 'ongkir',
                'rentals.image', 'rentals.created_at', 'no_inv', 'rentals.deleted_at', 'rentals.keterangan_item',
                'rentals.keterangan_acces', 'rentals.fee', 'rentals.tgl_inv',
                DB::raw('GROUP_CONCAT(b.name) as access')
            )
            ->groupBy(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start', 'date_pays',
                'rentals.date_end', 'rentals.status', 'a.rental_id', 'nominal_in', 'nominal_out', 'diskon', 'ongkir',
                'rentals.image', 'rentals.created_at', 'no_inv', 'rentals.deleted_at', 'rentals.keterangan_item',
                'rentals.keterangan_acces', 'rentals.fee', 'rentals.tgl_inv',
            )
            ->get();
        return view('employe.rental.history', compact('rentals'));
    }

}
