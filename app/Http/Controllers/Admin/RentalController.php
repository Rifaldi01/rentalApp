<?php

namespace App\Http\Controllers\Admin;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Item;
use App\Models\Debts;
use App\Models\Rental;
use App\Models\Problem;
use App\Models\Customer;
use App\Models\Accessories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AccessoriesCategory;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Intervention\Image\ImageManagerStatic;
use Intervention\Image\ImageManagerStatic as Image;

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

        foreach ($overdueRentals as $rental) {
            $rental->status = 2;
            $rental->save();

            Problem::create([
                'rental_id' => $rental->id,
                'descript' => 'Masa tenggang sudah berakhir tapi barang belum dikembalikan',
            ]);
        }
        $rent = Rental::latest()->paginate();
        $title = 'Delet Rental?';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $rental = Rental::leftjoin('accessories_categories as a', 'a.rental_id', '=', 'rentals.id')
            ->leftjoin('accessories as b', 'a.accessories_id', '=', 'b.id')
            ->select(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start',
                'rentals.date_end', 'rentals.status', 'a.rental_id', 'rentals.nominal_in', 'rentals.nominal_out', 'rentals.diskon', 'rentals.total_invoice',
                DB::raw('GROUP_CONCAT(b.name) as access')
            )
            ->groupBy(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start',
                'rentals.date_end', 'rentals.status', 'a.rental_id',  'rentals.nominal_in', 'rentals.nominal_out', 'rentals.diskon', 'rentals.total_invoice',
            )
            ->where('status', 1)
            ->get();
        foreach ($rental as $data) {
            $dateStart = Carbon::parse($data->date_start);
            $dateEnd = Carbon::parse($data->date_end);
            $daysDifference = $dateStart->diffInDays($dateEnd);
            $data->days_difference = $daysDifference;
        }
        return view('admin.rental.index', compact('rental'));
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
            'url' => route('admin.rental.store'),
            'cust' => $cust,
            'item' => $item,
            'acces' => $acces,
            'bank' => $bank,
            'debt' => $debt,
        ];

        if ($id) {
            $rental = Rental::findOrFail($id);
            $item = Item::where('status', '!=', 3)->get();
            $inject = [
                'url' => route('admin.rental.update', $id),
                'rental' => $rental,
                'cust' => $cust,
                'item' => $item,
                'acces' => $acces,
                'bank' => $bank,
                'debt' => $debt,
            ];
        }
        return view('admin.rental.create', $inject);
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
        Rental::whereId($id)->delete();
        return back();
    }

    public function save(Request $request, $id = null)
    {
        // Validasi input
        $validate = $request->validate([
            'item_id' => 'required|array',
            'customer_id' => 'required|exists:customers,id',
            'date_start' => 'required|date',
            'nominal_in' => 'required|numeric',
            'no_inv' => 'required',
            'total_invoice' => 'required',
            'date_end' => $id ? 'nullable' : 'required|date|after_or_equal:start_date',
        ], [
            'item_id.required' => 'Item Wajib diisi',
            'customer_id.required' => 'Customer Wajib diisi',
            'nominal_in.required' => 'Nominal In Wajib diisi',
            'date_start.required' => 'Tanggal Mulai Wajib diisi',
            'date_end.required' => 'Tanggal Selesai Wajib diisi',
            'no_inv.required' => 'No Invoice Wajib Diisi',
            'total_invoice.required' => 'Total Invoice Wajib Diisi',
            'date_end.after_or_equal' => 'Tanggal Selesai harus berupa tanggal setelah atau sama dengan tanggal mulai.'
        ]);

        // Proses aksesori
        $accessories = $request->input('access', []);
        $quantities = $request->input('accessories_quantity', []);

        // Temukan atau buat baru objek Rental
        $rental = Rental::firstOrNew(['id' => $id]);
        $isEdit = $rental->exists; // Cek apakah ini proses edit

        // Jika sedang mengedit, ambil data jumlah aksesori sebelumnya
        $previousAccessoriesData = [];
        if ($isEdit) {
            $previousAccessories = AccessoriesCategory::where('rental_id', $rental->id)->get();
            foreach ($previousAccessories as $previousAccessory) {
                $previousAccessoriesData[$previousAccessory->accessories_id] = $previousAccessory->accessories_quantity;
            }
        }

        // Periksa stok aksesori sebelum menyimpan
        foreach ($accessories as $index => $accessoryId) {
            $quantity = isset($quantities[$index]) ? $quantities[$index] : 0;
            $accessory = Accessories::find($accessoryId);

            if ($accessory) {
                $previousQuantity = $previousAccessoriesData[$accessoryId] ?? 0;
                $stockChange = $previousQuantity - $quantity;

                if ($accessory->stok + $stockChange < 0) {
                    return redirect()->back()->withErrors(['message' => 'Stok tidak mencukupi untuk aksesori ' . $accessory->name]);
                }
            }
        }

        // Simpan item_id sebelumnya untuk update status item
        $previousItemIds = json_decode($rental->item_id, true) ?? [];

        // Proses input rental
        $rental->customer_id = $request->input('customer_id');
        $rental->item_id = json_encode($request->item_id);
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
        $rental->no_inv = $request->input('no_inv');
        $rental->total_invoice = $request->input('total_invoice');
        $rental->tgl_inv = $request->input('tgl_inv');
        $rental->created_at = Carbon::now();
        $rental->status = 1;

        // Proses gambar jika ada
        if ($request->hasFile('image')) {
            $newImages = [];
            foreach ($request->file('image') as $file) {
                $file_name = md5(now()->timestamp . $file->getClientOriginalName()) . '.jpg';
                try {
                    $img = ImageManagerStatic::make($file);
                    $img->resize(null, 600, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $img->save(public_path("images/rental/{$file_name}"), 80, 'jpg');
                    $newImages[] = $file_name;
                } catch (\Exception $e) {
                    return back()->withErrors(['image' => 'Error processing the image: ' . $e->getMessage()])->withInput();
                }
            }
            $existingImages = json_decode($rental->image, true) ?? [];
            $rental->image = json_encode(array_merge($existingImages, $newImages));
        }

        $rental->save();

        // Simpan data aksesori
        $accessoriesData = [];
        foreach ($accessories as $index => $accessoryId) {
            $quantity = isset($quantities[$index]) ? $quantities[$index] : 0;
            $accessory = Accessories::find($accessoryId);
            if ($accessory) {
                $previousQuantity = $previousAccessoriesData[$accessoryId] ?? 0;
                $stockChange = $previousQuantity - $quantity;

                $accessoriesData[] = [
                    'rental_id' => $rental->id,
                    'accessories_id' => $accessoryId,
                    'accessories_quantity' => $quantity
                ];

                $accessory->stok += $stockChange;
                $accessory->save();
            }
        }

        AccessoriesCategory::where('rental_id', $rental->id)->delete();
        AccessoriesCategory::insert($accessoriesData);

        // Update status item
        $newItemIds = $request->input('item_id');
        $itemsToDeactivate = array_diff($previousItemIds, $newItemIds);
        $itemsToActivate = array_diff($newItemIds, $previousItemIds);

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

        // **Perbaikan di bagian Debts**
        if ($rental->nominal_in > 0) {
            $debt = Debts::where('rental_id', $rental->id)->first();
            if ($debt) {
                // Jika sudah ada, update data hutang
                $debt->update([
                    'bank_id'    => $request->input('bank_id'),
                    'pay_debts'  => $rental->nominal_in,
                    'penerima'   => $request->input('penerima'),
                    'date_pay'   => $request->input('date_pay'),
                    'description'=> $request->input('description'),
                ]);
            } else {
                // Jika belum ada, buat data baru
                Debts::create([
                    'rental_id'  => $rental->id,
                    'bank_id'    => $request->input('bank_id'),
                    'pay_debts'  => $rental->nominal_in,
                    'penerima'   => $request->input('penerima'),
                    'date_pay'   => $request->input('date_pay'),
                    'description'=> $request->input('description'),
                ]);
            }
        }

        Alert::success('Success', 'Rental has been saved!');
        return redirect()->route('admin.rental.index');
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
    public function hsty()
    {

        $rental = Rental::leftjoin('accessories_categories as a', 'a.rental_id', '=', 'rentals.id')
            ->leftjoin('accessories as b', 'a.accessories_id', '=', 'b.id')
            ->select(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po','rentals.date_start', 'date_pays',
                'rentals.date_end', 'rentals.status', 'a.rental_id', 'nominal_in', 'nominal_out', 'diskon', 'ongkir',
                'rentals.image', 'rentals.created_at', 'no_inv',
                DB::raw('GROUP_CONCAT(b.name) as access')
            )
            ->groupBy(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start', 'date_pays',
                'rentals.date_end', 'rentals.status', 'a.rental_id', 'nominal_in', 'nominal_out', 'diskon', 'ongkir',
                'rentals.image', 'rentals.created_at', 'no_inv',
            )
            ->get();
        return view('admin.rental.history', compact('rental'));
    }
    public  function tanggalBuat(Request $request, $id){
        $rental = Rental::findOrFail($id);
        $rental->created_at = $request->input('created_at');
        $rental->save();
        return redirect()->back()->withSuccess('Tanggal Disesuaikan');
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
