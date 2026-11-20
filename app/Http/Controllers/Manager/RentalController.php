<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Accessories;
use App\Models\AccessoriesCategory;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\Debts;
use App\Models\Item;
use App\Models\Problem;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Intervention\Image\ImageManagerStatic;
use RealRashid\SweetAlert\Facades\Alert;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $overdueRentals = Rental::where('date_end', '<', Carbon::now())
            ->where('status', 1)
            ->get();

        foreach ($overdueRentals as $rentals) {
            $rentals->status = 2;
            $rentals->save();

            Problem::create([
                'rental_id' => $rentals->id,
                'descript' => 'Masa tenggang sudah berakhir tapi barang belum dikembalikan',
            ]);
        }
        $title = 'Delet Rental?';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $rentals = Rental::leftjoin('accessories_categories as a', 'a.rental_id', '=', 'rentals.id')
            ->leftjoin('accessories as b', 'a.accessories_id', '=', 'b.id')
            ->select(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start', 'tgl_inv', 'fee', 'rentals.updated_at',
                'rentals.date_end', 'rentals.status', 'a.rental_id', 'rentals.nominal_in', 'rentals.nominal_out', 'rentals.no_inv', 'rentals.diskon', 'rentals.total_invoice',
                DB::raw('GROUP_CONCAT(b.name) as access')
            )
            ->groupBy(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start', 'tgl_inv', 'fee', 'rentals.updated_at',
                'rentals.date_end', 'rentals.status', 'a.rental_id', 'rentals.nominal_in', 'rentals.nominal_out', 'rentals.no_inv', 'rentals.diskon', 'rentals.total_invoice',
            )
            ->orderBy('rentals.created_at', 'DESC')
            ->get();
        foreach ($rentals as $data) {
            $dateStart = Carbon::parse($data->date_start);
            $dateEnd = Carbon::parse($data->date_end);
            $daysDifference = $dateStart->diffInDays($dateEnd);
            $data->days_difference = $daysDifference;
        }
        return view('manager.rental.index', compact('rentals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id = null)
    {
        $cust = Customer::pluck('name', 'id')->toArray();
        $item = Item::where('status', 0)->get();
        $acces = Accessories::all();
        $bank = Bank::pluck('name', 'id')->toArray();
        $debt = Debts::all();
        $inject = [
            'url' => route('manager.rental.store'),
            'cust' => $cust,
            'item' => $item,
            'acces' => $acces,
            'bank' => $bank,
            'debt' => $debt,
        ];

        if ($id) {
            $rentals = Rental::findOrFail($id);
            $item = Item::where('status', '!=', 3)->get();
            $inject = [
                'url' => route('manager.rental.update', $id),
                'rentals' => $rentals,
                'cust' => $cust,
                'item' => $item,
                'acces' => $acces,
                'bank' => $bank,
                'debt' => $debt,
            ];
        }
        return view('manager.rental.create', $inject);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'date_end' => 'after_or_equal:start_date',
        ],[
            'date_end.after_or_equal' => 'Tanggal akhir harus berupa tanggal setelah atau sama dengan tanggal mulai.'
        ]);
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
        // Menghapus rental berdasarkan id
        $rental = Rental::findOrFail($id);
        $rental->delete();

        // Menghapus problem yang memiliki rental_id sesuai id yang dihapus
        Problem::where('rental_id', $id)->delete();

        // Menghapus debt yang memiliki rental_id sesuai id yang dihapus
        Debts::where('rental_id', $id)->delete();

        return back()->withSuccess('Rental berhasil dihapus');
    }
    public function save(Request $request, $id = null)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. VALIDASI INPUT
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'item_id' => 'required|array|min:1',
            'item_id.*' => 'required|exists:items,id',

            'customer_id' => 'required|exists:customers,id',

            'date_start' => 'required|date',

            'date_end' => $id
                ? 'nullable|date|after_or_equal:date_start'
                : 'required|date|after_or_equal:date_start',

            'nominal_in' => 'required|numeric|min:0',

            'total_invoice' => 'required',

            'access' => 'nullable|array',
            'access.*' => 'nullable|exists:accessories,id',

            'accessories_quantity' => 'nullable|array',
            'accessories_quantity.*' => 'nullable|integer|min:1',

            'image' => 'nullable|array',
            'image.*' => 'image|mimes:jpg,jpeg,png,webp|max:10240',
        ], [
            'item_id.required' => 'Item wajib diisi.',
            'item_id.array' => 'Format item tidak valid.',
            'item_id.min' => 'Minimal harus memilih satu item.',
            'item_id.*.exists' => 'Terdapat item yang tidak ditemukan.',

            'customer_id.required' => 'Customer wajib diisi.',
            'customer_id.exists' => 'Customer tidak ditemukan.',

            'date_start.required' => 'Tanggal mulai wajib diisi.',
            'date_end.required' => 'Tanggal selesai wajib diisi.',
            'date_end.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',

            'nominal_in.required' => 'Nominal in wajib diisi.',
            'nominal_in.numeric' => 'Nominal in harus berupa angka.',
            'nominal_in.min' => 'Nominal in tidak boleh kurang dari 0.',

            'total_invoice.required' => 'Total invoice wajib diisi.',

            'access.*.exists' => 'Terdapat accessories yang tidak ditemukan.',
            'accessories_quantity.*.integer' => 'Jumlah accessories harus berupa angka bulat.',
            'accessories_quantity.*.min' => 'Jumlah accessories minimal 1.',

            'image.*.image' => 'File yang diunggah harus berupa gambar.',
            'image.*.max' => 'Ukuran gambar maksimal 10 MB.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2. PERSIAPAN DATA
        |--------------------------------------------------------------------------
        */

        $accessories = $request->input('access', []);
        $quantities = $request->input('accessories_quantity', []);

        $accessories = is_array($accessories) ? $accessories : [];
        $quantities = is_array($quantities) ? $quantities : [];

        /*
         * Gabungkan accessories berdasarkan ID.
         * Berguna apabila terdapat ID accessories yang sama
         * pada beberapa baris input.
         */

        $newAccessoriesData = [];

        foreach ($accessories as $index => $accessoryId) {

            if (empty($accessoryId)) {
                continue;
            }

            $quantity = (int) ($quantities[$index] ?? 0);

            if ($quantity <= 0) {
                throw ValidationException::withMessages([
                    'access' => 'Jumlah accessories harus lebih dari 0.',
                ]);
            }

            if (!isset($newAccessoriesData[$accessoryId])) {
                $newAccessoriesData[$accessoryId] = 0;
            }

            $newAccessoriesData[$accessoryId] += $quantity;
        }

        /*
        |--------------------------------------------------------------------------
        | 3. PERSIAPAN FILE GAMBAR
        |--------------------------------------------------------------------------
        */

        $uploadedImagePaths = [];

        try {

            /*
            |--------------------------------------------------------------------------
            | 4. TRANSAKSI DATABASE
            |--------------------------------------------------------------------------
            */

            $rental = DB::transaction(
                function () use ($request, $id, $newAccessoriesData, &$uploadedImagePaths) {

                    /*
                    |--------------------------------------------------------------------------
                    | 4.1 AMBIL DATA RENTAL
                    |--------------------------------------------------------------------------
                    */

                    if ($id) {
                        $rental = Rental::whereKey($id)->lockForUpdate()->firstOrFail();
                        $isEdit = true;
                    } else {
                        $rental = new Rental();
                        $isEdit = false;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | 4.2 SIMPAN DATA LAMA
                    |--------------------------------------------------------------------------
                    */

                    $previousCustomerId = $isEdit ? $rental->customer_id : null;
                    $oldInvoice = $isEdit ? (int) $rental->total_invoice : 0;
                    $oldDiskon = $isEdit ? (float) $rental->diskon : 0;
                    $oldFee = $isEdit ? (float) $rental->fee : 0;

                    /*
                     * Ambil item sebelumnya.
                     */

                    $previousItemIds = $isEdit ? (json_decode($rental->item_id, true) ?? []) : [];
                    $previousItemIds = array_map('strval', $previousItemIds);

                    /*
                     * Ambil accessories sebelumnya.
                     * Jumlah digabung berdasarkan accessories_id.
                     */

                    $previousAccessoriesData = [];
                    if ($isEdit) {$previousAccessories = AccessoriesCategory::where('rental_id', $rental->id)->get();
                        foreach ($previousAccessories as $previousAccessory) {
                            $accessoryId = $previousAccessory->accessories_id;
                            if (!isset($previousAccessoriesData[$accessoryId])) {
                                $previousAccessoriesData[$accessoryId] = 0;
                            }
                            $previousAccessoriesData[$accessoryId] +=
                                (int) $previousAccessory->accessories_quantity;
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | 4.3 UPDATE STOK ACCESSORIES
                    |--------------------------------------------------------------------------
                    |
                    | Rumus:
                    |
                    | Stok akhir = Stok sekarang + Jumlah lama - Jumlah baru
                    |
                    | Contoh:
                    | Stok sekarang = 10
                    | Jumlah lama   = 5
                    | Jumlah baru   = 8
                    |
                    | Stok akhir    = 10 + 5 - 8 = 7
                    |
                    | Accessories yang dihapus dari rental juga akan
                    | mengembalikan stoknya.
                    |--------------------------------------------------------------------------
                    */

                    $allAccessoryIds = array_unique(array_merge(array_keys($previousAccessoriesData),
                        array_keys($newAccessoriesData)
                    ));
                    foreach ($allAccessoryIds as $accessoryId) {
                        $accessory = Accessories::whereKey($accessoryId)->lockForUpdate()->first();
                        if (!$accessory) {
                            throw ValidationException::withMessages([
                                'access' => 'Accessories dengan ID ' . $accessoryId . ' tidak ditemukan.',
                            ]);
                        }
                        $oldQuantity = (int) ($previousAccessoriesData[$accessoryId] ?? 0);
                        $newQuantity = (int) ($newAccessoriesData[$accessoryId] ?? 0);
                        $currentStock = (int) $accessory->stok;
                        $availableStock = $currentStock + $oldQuantity;
                        if ($availableStock < $newQuantity) {
                            throw ValidationException::withMessages([
                                'access' => 'Stok tidak mencukupi untuk accessories '
                                    . $accessory->name . '. Stok tersedia: '
                                    . $availableStock . ', jumlah diminta: '
                                    . $newQuantity,
                            ]);
                        }
                        $stockChange = $oldQuantity - $newQuantity;
                        $accessory->stok = $currentStock + $stockChange;
                        $accessory->save();
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | 4.4 GENERATE NOMOR INVOICE
                    |--------------------------------------------------------------------------
                    */

                    $currentYear = date('Y');
                    $currentMonthNumber = str_pad(date('n'), 2, '0', STR_PAD_LEFT);
                    $invoiceNumber = $rental->no_inv;
                    if (!$isEdit || empty($invoiceNumber)) {
                        $lastInvoice = Rental::whereYear('created_at', $currentYear)
                            ->where('no_inv', 'like', "INV/DND/%/%/{$currentYear}")->orderByDesc('id')->lockForUpdate()
                            ->first();
                        $lastNumber = 0;
                        if ($lastInvoice && preg_match("/^INV\\/DND\\/(\\d{4})\\/\\d{2}\\/{$currentYear}$/", $lastInvoice->no_inv,
                                $matches
                            )
                        ) {
                            $lastNumber = (int) $matches[1];
                        }
                        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
                        $invoiceNumber = "INV/DND/{$nextNumber}/{$currentMonthNumber}/{$currentYear}";
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | 4.5 SIMPAN DATA RENTAL
                    |--------------------------------------------------------------------------
                    */

                    $rental->customer_id = $request->input('customer_id');
                    $rental->item_id = json_encode($request->input('item_id'));
                    $rental->name_company = $request->input('name_company');
                    $rental->phone_company = $request->input('phone_company');
                    $rental->addres_company = $request->input('addres_company');
                    $rental->no_po = $request->input('no_po');
                    $rental->date_start = $request->input('date_start');
                    $rental->date_end = $request->input('date_end');
                    $rental->nominal_in = $request->input('nominal_in');
                    $rental->nominal_out = $request->input('nominal_out') ?? 0;
                    $rental->diskon = $request->input('diskon') ?? 0;
                    $rental->date_pays = $request->input('date_pays');
                    $rental->ppn = $request->input('ppn');
                    $rental->no_inv = $invoiceNumber;
                    $rental->total_invoice = $request->input('total_invoice');
                    $rental->tgl_inv = $request->input('tgl_inv');
                    $rental->fee = $request->input('fee') ?? 0;
                    $rental->biaya_ganti = $request->input('biaya_ganti') ?? 0;
                    $rental->keterangan_item = $request->input('keterangan_item');
                    $rental->keterangan_acces = $request->input('keterangan_acces');
                    $rental->status = 3;

                    /*
                     * created_at hanya diisi saat membuat rental baru.
                     * Jangan mengubah tanggal pembuatan ketika edit.
                     */

                    if (!$isEdit) {
                        $rental->created_at = now();
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | 4.6 PROSES UPLOAD GAMBAR
                    |--------------------------------------------------------------------------
                    */

                    if ($request->hasFile('image')) {$newImages = [];foreach ($request->file('image') as $file) {
                        try {
                            $fileName = md5(uniqid('', true) . $file->getClientOriginalName()) . '.jpg';
                            $imagePath = public_path("images/rental/{$fileName}");

                            /*
                             * Menggunakan ImageManagerStatic
                             * sesuai implementasi controller sebelumnya.
                             */

                            $img = ImageManagerStatic::make($file);
                            $img->resize(null, 600, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            }
                            );
                            $directory = public_path('images/rental');
                            if (!is_dir($directory)) {mkdir($directory, 0755, true);}
                            $img->save($imagePath, 80, 'jpg');
                            $uploadedImagePaths[] = $imagePath;
                            $newImages[] = $fileName;
                        } catch (\Throwable $e) {
                            throw ValidationException::withMessages(['image' => 'Gagal memproses gambar: ' . $e->getMessage(),]);
                        }
                    }
                        $existingImages = json_decode($rental->image, true) ?? [];
                        $rental->image = json_encode(array_merge($existingImages, $newImages));
                    }
                    $rental->save();

                    /*
                    |--------------------------------------------------------------------------
                    | 4.7 SIMPAN DETAIL ACCESSORIES
                    |--------------------------------------------------------------------------
                    */

                    AccessoriesCategory::where('rental_id', $rental->id)->delete();
                    $accessoriesData = [];
                    foreach ($newAccessoriesData as $accessoryId => $quantity) {
                        $accessoriesData[] = [
                            'rental_id' => $rental->id,
                            'accessories_id' => $accessoryId,
                            'accessories_quantity' => $quantity,
                        ];
                    }
                    if (!empty($accessoriesData)) {
                        AccessoriesCategory::insert($accessoriesData);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | 4.8 UPDATE STATUS ITEM
                    |--------------------------------------------------------------------------
                    */

                    $newItemIds = array_map('strval', $request->input('item_id'));
                    $itemsToDeactivate = array_diff($previousItemIds, $newItemIds);
                    $itemsToActivate = array_diff($newItemIds, $previousItemIds);

                    /*
                     * Item yang sebelumnya dipakai rental tetapi sekarang
                     * dihapus dari rental dikembalikan menjadi status 0.
                     */

                    foreach ($itemsToDeactivate as $itemId) {
                        $item = Item::find($itemId);
                        if ($item) {
                            $item->status = 0;
                            $item->save();
                        }
                    }

                    /*
                     * Item yang baru ditambahkan menjadi status 2.
                     */

                    foreach ($itemsToActivate as $itemId) {
                        $item = Item::find($itemId);
                        if ($item) {
                            $item->status = 2;
                            $item->save();
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | 4.9 SIMPAN / UPDATE DEBTS
                    |--------------------------------------------------------------------------
                    */

                    if ((float) $rental->nominal_in > 0) {
                        $debt = Debts::where('rental_id', $rental->id)->first();
                        $debtData = [
                            'bank_id' => $request->input('bank_id'),
                            'pay_debts' => $rental->nominal_in,
                            'penerima' => $request->input('penerima'),
                            'date_pay' => $request->input('date_pay'),
                            'description' => $request->input('description'),
                        ];
                        if ($debt) {
                            $debt->update($debtData);
                        } else {
                            Debts::create(array_merge(
                                ['rental_id' => $rental->id,], $debtData
                            ));
                        }
                    }

                    /*
                     * Perilaku sebelumnya tetap dipertahankan:
                     * nominal_in = 0 tidak menghapus data Debts.
                     */

                    /*
                    |--------------------------------------------------------------------------
                    | 4.10 HITUNG POIN CUSTOMER
                    |--------------------------------------------------------------------------
                    */

                    $fee = (float) ($rental->fee ?? 0);
                    $diskon = (float) ($rental->diskon ?? 0);

                    /*
                     * Poin berdasarkan data lama.
                     */

                    $previousPoints = 0;
                    if ($isEdit && $oldInvoice >= 4000000 && $oldFee == 0 && $oldDiskon == 0) {
                        $previousPoints = intdiv($oldInvoice, 4000000);
                    }

                    /*
                     * Poin berdasarkan data baru.
                     */

                    $newPoints = 0;
                    if ((int) $rental->total_invoice >= 4000000 && $fee == 0 && $diskon == 0) {
                        $newPoints = intdiv((int) $rental->total_invoice, 4000000);
                    }

                    /*
                     * CREATE:
                     * Tambahkan poin customer baru.
                     */

                    if (!$isEdit && $newPoints > 0) {
                        $customer = Customer::whereKey($rental->customer_id)->lockForUpdate()->first();
                        if ($customer) {
                            $customer->point_rental = max(0, (int) ($customer->point_rental ?? 0) + $newPoints);
                            $customer->save();
                        }
                    }

                    /*
                     * EDIT:
                     * Jika customer berubah, kurangi poin customer lama
                     * dan tambahkan poin ke customer baru.
                     *
                     * Perilaku ini mengikuti controller sebelumnya.
                     */

                    if ($isEdit && $previousCustomerId && $previousCustomerId != $rental->customer_id) {
                        if ($previousPoints > 0) {$oldCustomer = Customer::whereKey($previousCustomerId)->lockForUpdate()->first();
                            if ($oldCustomer) {$oldCustomer->point_rental = max(0, (int) ($oldCustomer->point_rental ?? 0) - $previousPoints);
                                $oldCustomer->save();
                            }
                        }
                        if ($newPoints > 0) {$newCustomer = Customer::whereKey($rental->customer_id)->lockForUpdate()->first();
                            if ($newCustomer) {
                                $newCustomer->point_rental = max(0, (int) ($newCustomer->point_rental ?? 0) + $newPoints);
                                $newCustomer->save();
                            }
                        }
                    }
                    return $rental;
                },
                3
            );

            /*
            |--------------------------------------------------------------------------
            | 5. RESPONSE BERHASIL
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,
                'message' => 'Rental berhasil disimpan!',
                'redirect' => route('manager.rental.index'),
                'rental_id' => $rental->id,
            ], 200);

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | 6. HAPUS FILE GAMBAR JIKA TRANSAKSI GAGAL
            |--------------------------------------------------------------------------
            */

            foreach ($uploadedImagePaths as $imagePath) {
                if (file_exists($imagePath)) {
                    @unlink($imagePath);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 7. RESPONSE ERROR
            |--------------------------------------------------------------------------
            */

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                throw $e;
            }

            \Illuminate\Support\Facades\Log::error(
                'Rental Save Error: ' . $e->getMessage(),
                [
                    'rental_id' => $id,
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan rental. Silakan periksa kembali data atau hubungi administrator.',
            ], 500);
        }
    }

    public function finis($id)
{
    // Temukan objek rental berdasarkan ID
    $rental = Rental::findOrFail($id);
    $rental->status = 0;
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

    return redirect()->back()->with('success', 'Rental Finished');
}
    public function problem($id)
    {
        $rental = Rental::findOrFail($id);
        $rental->status = 2;
        $rental->save();
        Alert::warning('Warning', 'The Rental Has Problem');
        return redirect()->back();
    }
    public function hsty(Request $request)
    {
        $title = 'Delete Rental?';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        // Dropdown Tahun
        $listTahun = Rental::selectRaw('YEAR(tgl_inv) as thn')
            ->distinct()
            ->orderBy('thn', 'DESC')
            ->get();

        $tahun = $request->tahun;
        $bulan = $request->bulan;

        $rentals = Rental::leftJoin('accessories_categories as a', 'a.rental_id', '=', 'rentals.id')
            ->leftJoin('accessories as b', 'a.accessories_id', '=', 'b.id')
            ->select(
                'rentals.id',
                'rentals.customer_id',
                'rentals.item_id',
                'rentals.name_company',
                'rentals.addres_company',
                'rentals.phone_company',
                'rentals.no_po',
                'rentals.date_start',
                'date_pays',
                'rentals.date_end',
                'rentals.status',
                'a.rental_id',
                'nominal_in',
                'nominal_out',
                'diskon',
                'ongkir',
                'rentals.image',
                'rentals.created_at',
                'no_inv',
                'rentals.deleted_at',
                'rentals.keterangan_item',
                'rentals.keterangan_acces',
                'rentals.fee',
                'rentals.tgl_inv',
                'rentals.updated_at',
                'rentals.total_invoice',
                'ppn',
                DB::raw('GROUP_CONCAT(b.name) as access')
            );

        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        // Pertama kali membuka halaman
        if (!$request->has('tahun') && !$request->has('bulan')) {

            $rentals->whereYear('rentals.tgl_inv', now()->year)
                ->whereMonth('rentals.tgl_inv', now()->month);

        } else {

            // Tahun
            if (!empty($tahun) && $tahun != 'all') {
                $rentals->whereYear('rentals.tgl_inv', $tahun);
            }

            // Bulan
            if (!empty($bulan) && $bulan != 'all') {
                $rentals->whereMonth('rentals.tgl_inv', $bulan);
            }
        }

        $rentals = $rentals
            ->groupBy(
                'rentals.id',
                'rentals.customer_id',
                'rentals.item_id',
                'rentals.name_company',
                'rentals.addres_company',
                'rentals.phone_company',
                'rentals.no_po',
                'rentals.date_start',
                'date_pays',
                'rentals.date_end',
                'rentals.status',
                'a.rental_id',
                'nominal_in',
                'nominal_out',
                'diskon',
                'ongkir',
                'rentals.image',
                'rentals.created_at',
                'no_inv',
                'rentals.deleted_at',
                'rentals.keterangan_item',
                'rentals.keterangan_acces',
                'rentals.fee',
                'rentals.tgl_inv',
                'rentals.updated_at',
                'rentals.total_invoice',
                'ppn'
            )
            ->orderBy('rentals.tgl_inv', 'DESC')
            ->get();

        return view('manager.rental.history', compact(
            'rentals',
            'listTahun',
            'tahun',
            'bulan'
        ));
    }
    public function deleteImage(Request $request)
    {
        $image = $request->input('image');
        $customer = Rental::whereJsonContains('image', $image)->first();

        if ($customer) {
            $images = json_decode($customer->image);
            if (($key = array_search($image, $images)) !== false) {
                unset($images[$key]);
            }
            $customer->image = json_encode(array_values($images));

            // Delete the actual file
            if (file_exists(public_path('images/identity/' . $image))) {
                unlink(public_path('images/identity/' . $image));
            }

            $customer->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
    public function divisi()
    {
        $title = 'Delete Rental?';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        $rental = Rental::leftJoin('customers as c', 'rentals.customer_id', '=', 'c.id')
            ->leftJoin('accessories_categories as a', 'a.rental_id', '=', 'rentals.id')
            ->leftJoin('accessories as b', 'a.accessories_id', '=', 'b.id')
            ->select(
                'rentals.id',
                'rentals.customer_id',
                'c.name as customer_name',
                'rentals.item_id',
                'rentals.name_company',
                'rentals.addres_company',
                'rentals.phone_company',
                'rentals.no_po',
                'rentals.date_start',
                'rentals.date_end',
                'rentals.status',
                'a.rental_id',
                'rentals.nominal_in',
                'rentals.nominal_out',
                'rentals.no_inv',
                'rentals.diskon',
                'rentals.total_invoice',
                DB::raw('GROUP_CONCAT(b.name) as access')
            )
            ->whereIn('c.name', [
                'Adhijasa',
                'Darwis Internasiol Survey',
                'Satelit Surveying Official',
                'Dhyas Survey Official'
            ])
            ->groupBy(
                'rentals.id',
                'rentals.customer_id',
                'c.name',
                'rentals.item_id',
                'rentals.name_company',
                'rentals.addres_company',
                'rentals.phone_company',
                'rentals.no_po',
                'rentals.date_start',
                'rentals.date_end',
                'rentals.status',
                'a.rental_id',
                'rentals.nominal_in',
                'rentals.nominal_out',
                'rentals.no_inv',
                'rentals.diskon',
                'rentals.total_invoice',
            )
            ->orderBy('rentals.created_at', 'DESC')
            ->get();

        foreach ($rental as $data) {
            $dateStart = Carbon::parse($data->date_start);
            $dateEnd = Carbon::parse($data->date_end);
            $daysDifference = $dateStart->diffInDays($dateEnd);
            $data->days_difference = $daysDifference;
        }

        return view('manager.rental.divisi', compact('rental'));
    }
    public function downloadImages($id)
    {
        $rental = Rental::findOrFail($id);
        $images = json_decode($rental->image);

        // Ambil nama pelanggan dari model Rental
        $customerName = $rental->cust->name ?? 'unknown_customer'; // Ganti dengan nama kolom yang sesuai

        // Sanitasi nama pelanggan untuk penggunaan dalam nama file
        $sanitizedCustomerName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $customerName);

        if ($images && count($images) > 0) {
            $zip = new ZipArchive;
            $fileName = $sanitizedCustomerName . '_rental_' . $id . '.zip';
            $zipPath = public_path($fileName);

            // Membuka file zip
            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                foreach ($images as $image) {
                    $filePath = public_path('images/rental/' . $image);

                    // Periksa apakah file ada sebelum menambahkannya
                    if (file_exists($filePath)) {
                        $zip->addFile($filePath, $image);
                    } else {
                        // Tambahkan log error atau penanganan jika file tidak ditemukan
                        $zip->addFromString($image, "File not found: $filePath");
                    }
                }
                $zip->close();
            }

            return response()->download($zipPath)->deleteFileAfterSend(true);
        } else {
            return redirect()->back()->with('error', 'No images found.');
        }
    }
}
