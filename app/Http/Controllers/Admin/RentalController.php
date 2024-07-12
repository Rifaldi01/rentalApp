<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accessories;
use App\Models\AccessoriesCategory;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Problem;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic;
use Intervention\Image\ImageManagerStatic as Image;
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
                'rentals.date_end', 'rentals.status', 'a.rental_id',
                \DB::raw('GROUP_CONCAT(b.name) as access')
            )
            ->groupBy(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start',
                'rentals.date_end', 'rentals.status', 'a.rental_id'
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
        $item = Item::where('status', 0)->get()->mapWithKeys(function ($item) {
            return [$item->id => $item->name . ' (' . $item->no_seri . ')'];
        })->toArray();
        $acces = Accessories::all();
        $inject = [
            'url' => route('admin.rental.store'),
            'cust' => $cust,
            'item' => $item,
            'acces' => $acces
        ];

        if ($id) {
            $rental = Rental::findOrFail($id);
            $item = Item::where('status', '!=', 3)->get()->mapWithKeys(function ($item) {
                return [$item->id => $item->name . ' (' . $item->no_seri . ')'];
            })->toArray();
            $inject = [
                'url' => route('admin.rental.update', $id),
                'rental' => $rental,
                'cust' => $cust,
                'item' => $item,
                'acces' => $acces
            ];
        }

        return view('admin.rental.create', $inject);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'date_end' => 'after_or_equal:start_date',
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
        $validate = $request->validate([
            'date_end' => 'after_or_equal:today',
        ]);
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
            'item_id' => 'required|exists:items,id',
            'customer_id' => 'required|exists:customers,id',
            'date_start' => 'required|date',
            'date_end' => 'required|date',
            'image' => $id ? 'nullable|image' : 'required|image',
            'nominal_in' => 'required|numeric',
            'diskon' => 'numeric'
        ]);

        // Proses accessories
        $accessories = $request->input('access', []);
        $quantities = $request->input('accessories_quantity', []);

        // Temukan atau buat baru objek Rental
        $rental = Rental::firstOrNew(['id' => $id]);
        $isEdit = $rental->exists; // Tambahkan: Cek apakah ini proses edit

        // Jika sedang mengedit, ambil data jumlah accessories sebelumnya
        $previousAccessoriesData = []; // Tambahkan: Array untuk menyimpan data accessories sebelumnya
        if ($isEdit) {
            $previousAccessories = AccessoriesCategory::where('rental_id', $rental->id)->get();
            foreach ($previousAccessories as $previousAccessory) {
                $previousAccessoriesData[$previousAccessory->accessories_id] = $previousAccessory->accessories_quantity;
            }
        }

        // Simpan item_id sebelumnya untuk update status item
        $previousItemId = $rental->item_id;

        $rental->customer_id = $request->input('customer_id');
        $rental->item_id = $request->input('item_id');
        $rental->name_company = $request->input('name_company');
        $rental->phone_company = $request->input('phone_company');
        $rental->addres_company = $request->input('addres_company');
        $rental->no_po = $request->input('no_po');
        $rental->date_start = $request->input('date_start');
        $rental->date_end = $request->input('date_end');
        $rental->nominal_in = $request->input('nominal_in');
        $rental->nominal_out = $request->input('nominal_out');
        $rental->ongkir = $request->input('ongkir');
        $rental->diskon = $request->input('diskon');
        $rental->status = 1;

        // Proses gambar jika ada
        if ($request->hasFile('image')) {
            if ($rental->image && file_exists(public_path('images/rental/' . $rental->image))) {
                unlink(public_path('images/rental/' . $rental->image));
            }

            $file = $request->file('image');
            $file_name = md5(now()).'.jpg';

            $img = ImageManagerStatic::make($file);
            $img = $img->resize(null, 600, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save(public_path("images/rental/{$file_name}"), 50, 'jpg');

            $rental->image = $file_name;
        }

        $rental->save();

        $accessoriesData = [];
        foreach ($accessories as $index => $accessoryId) {
            $quantity = isset($quantities[$index]) ? $quantities[$index] : 0;
            $accessory = Accessories::find($accessoryId);

            if ($accessory) {
                $previousQuantity = $previousAccessoriesData[$accessoryId] ?? 0; // Tambahkan: Ambil jumlah accessories sebelumnya
                $stockChange = $previousQuantity - $quantity; // Tambahkan: Hitung perubahan stok

                // Jika stok tidak mencukupi, tampilkan pesan error
                if ($accessory->stok + $stockChange < 0) {
                    return redirect()->back()->withErrors(['message' => 'Stok tidak mencukupi untuk aksesoris ' . $accessory->name]);
                }

                $accessoriesData[] = [
                    'rental_id' => $rental->id,
                    'accessories_id' => $accessoryId,
                    'accessories_quantity' => $quantity
                ];

                $accessory->stok += $stockChange; // Tambahkan: Update stok accessories
                $accessory->save();
            }
        }

        // Update accessories categories
        AccessoriesCategory::where('rental_id', $rental->id)->delete();
        AccessoriesCategory::insert($accessoriesData);

        // Update item status
        if ($isEdit && $previousItemId != $request->input('item_id')) {
            // Jika item sebelumnya berbeda dengan item baru, update status item sebelumnya
            $previousItem = Item::find($previousItemId);
            if ($previousItem) {
                $previousItem->status = 0; // Ubah status item sebelumnya menjadi 0
                $previousItem->save();
            }
        }

        $item = Item::find($request->input('item_id'));
        $item->status = 2;
        $item->save();

        Alert::success('Success', 'Rental has been saved!');
        return redirect()->route('admin.rental.index');
    }


    public function finis($id)
    {
        $rental = Rental::findOrFail($id);
        $rental->status = 0;
        $rental->save();

        $item = Item::findOrFail($rental->item_id);
        $item->status = 0;
        $item->save();

        // Mengembalikan stok aksesori
        $accessoriesCategories = AccessoriesCategory::where('rental_id', $rental->id)->get();
        foreach ($accessoriesCategories as $accesCategory) {
            $accessory = Accessories::find($accesCategory->accessories_id);
            if ($accessory) {
                $accessory->stok += $accesCategory->accessories_quantity;
                $accessory->save();
            }
        }

        Alert::success('Success', 'Rental has been Finished');
        return redirect()->back();
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
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po','rentals.date_start',
                'rentals.date_end', 'rentals.status', 'a.rental_id', 'nominal_in', 'nominal_out', 'diskon', 'ongkir',
                \DB::raw('GROUP_CONCAT(b.name) as access')
            )
            ->groupBy(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start',
                'rentals.date_end', 'rentals.status', 'a.rental_id', 'nominal_in', 'nominal_out', 'diskon', 'ongkir',
            )
            ->get();
        return view('admin.rental.history', compact('rental'));
    }
}
