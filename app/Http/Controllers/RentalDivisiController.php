<?php

namespace App\Http\Controllers;

use App\Models\Accessories;
use App\Models\Divisi;
use App\Models\Item;
use App\Models\RentalDivisi;
use App\Models\RentalDivisiDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic;


class RentalDivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rentalDivisi = RentalDivisi::all();
        return view('employe.rentalDivisi.index', compact('rentalDivisi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id = null)
    {
        $divisi = Divisi::pluck('name', 'id')->toArray();
        $item = Item::where('status', 0)->get();
        $acces = Accessories::all();
        $inject = [
            'url' => route('employe.rentaldivisi.store'),
            'divisi' => $divisi,
            'item' => $item,
            'acces' => $acces,
        ];

        if ($id) {
            $rentalDivisi = RentalDivisi::findOrFail($id);
            $item = Item::where('status', '!=', 3)->get();

            $inject = [
                'url' => route('employe.rentaldivisi.update', $id),
                'rentalDivisi' => $rentalDivisi,
                'divisi' => $divisi,
                'item' => $item,
                'acces' => $acces,
            ];
        }

        return view('employe.rentalDivisi.create', $inject);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->save($request);
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
        return $this->create($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        return $this->save($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        RentalDivisi::whereId($id)->delete();
        return back();
    }

    public function save(Request $request, $id = null)
    {
        // === VALIDASI INPUT ===
        $validate = $request->validate([
            'divisi_id' => 'required|exists:customers,id',
        ], [
            'divisi_id.required' => 'Divisi wajib diisi',
        ]);

        // Ambil data input
        $accessories = $request->input('access', []);
        $quantities  = $request->input('qty', []);
        $itemIds     = $request->input('item_id', []); // Tidak wajib

        try {
            DB::beginTransaction();

            // === TEMUKAN / BUAT DATA RENTAL DIVISI ===
            $rentalDivisi = RentalDivisi::firstOrNew(['id' => $id]);
            $isEdit = $rentalDivisi->exists;

            // Simpan stok lama jika edit
            $previousAccessoriesData = [];
            if ($isEdit) {
                $previousAccessories = RentalDivisiDetail::where('rental_divisi_id', $rentalDivisi->id)->get();
                foreach ($previousAccessories as $prev) {
                    $previousAccessoriesData[$prev->accessories_id] = $prev->qty;
                }
            }

            // === CEK STOK AKSESORI ===
            foreach ($accessories as $index => $accessoryId) {
                $quantity  = isset($quantities[$index]) ? $quantities[$index] : 0;
                $accessory = Accessories::find($accessoryId);

                if ($accessory) {
                    $previousQty = $previousAccessoriesData[$accessoryId] ?? 0;
                    $stockChange = $previousQty - $quantity;

                    if ($accessory->stok + $stockChange < 0) {
                        throw new \Exception('Stok tidak mencukupi untuk aksesori ' . $accessory->name);
                    }
                }
            }

            // === SIMPAN DATA RENTAL DIVISI ===
            $previousItemIds = json_decode($rentalDivisi->item_id, true) ?? [];

            $rentalDivisi->divisi_id     = $request->input('divisi_id');
            $rentalDivisi->item_id       = $itemIds ? json_encode($itemIds) : json_encode([]);
            $rentalDivisi->description   = $request->input('description');
            $rentalDivisi->kode_pinjaman = $rentalDivisi->kode_pinjaman ?? 'REQ-' . strtoupper(uniqid());
            $rentalDivisi->save();

            // === SIMPAN DATA AKSESORI ===
            $accessoriesData = [];
            foreach ($accessories as $index => $accessoryId) {
                $quantity  = isset($quantities[$index]) ? $quantities[$index] : 0;
                $accessory = Accessories::find($accessoryId);

                if ($accessory) {
                    $previousQty = $previousAccessoriesData[$accessoryId] ?? 0;
                    $stockChange = $previousQty - $quantity;

                    // Update stok
                    $accessory->stok += $stockChange;
                    $accessory->save();

                    $accessoriesData[] = [
                        'rental_divisi_id' => $rentalDivisi->id,
                        'accessories_id'   => $accessoryId,
                        'qty'              => $quantity,
                    ];
                }
            }

            RentalDivisiDetail::where('rental_divisi_id', $rentalDivisi->id)->delete();
            if (!empty($accessoriesData)) {
                RentalDivisiDetail::insert($accessoriesData);
            }

            // === UPDATE STATUS ITEM ===
            $newItemIds = is_array($itemIds) ? $itemIds : [];
            $itemsToDeactivate = array_diff($previousItemIds, $newItemIds);
            $itemsToActivate   = array_diff($newItemIds, $previousItemIds);

            foreach ($itemsToDeactivate as $itemId) {
                $item = Item::find($itemId);
                if ($item) {
                    $item->status = 0;
                    $item->save();
                }
            }

            foreach ($itemsToActivate as $itemId) {
                $item = Item::find($itemId);
                if ($item) {
                    $item->status = 2;
                    $item->save();
                }
            }

            DB::commit();

            return redirect()->back()->withSuccess('Rental berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }
    public function finis($id)
    {
        // Temukan objek rental berdasarkan ID
        $rental = RentalDivisi::findOrFail($id);
        $rental->status = 1;
        $rental->save();

        // Ambil ID item dari rental
        $itemIds = json_decode($rental->item_id, true) ?? []; // Jika item_id disimpan dalam format JSON

        // Update status item menjadi 0
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
        $accessoriesCategories = RentalDivisiDetail::where('rental_divisi_id', $rental->id)->get();
        foreach ($accessoriesCategories as $accessoriesCategory) {
            // Update status_acces di tabel pivot
            RentalDivisiDetail::where('rental_divisi_id', $rental->id)
                ->where('accessories_id', $accessoriesCategory->accessories_id)
                ->update(['status' => 1]);

            // Kembalikan stok aksesori
            $accessory = Accessories::find($accessoriesCategory->accessories_id);
            if ($accessory) {
                $accessory->stok += $accessoriesCategory->qty;
                $accessory->save();
            }
        }

        return redirect()->back()->with('success', 'Rental Finished');
    }

}
