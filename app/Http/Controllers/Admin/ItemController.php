<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemSale;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic;
use RealRashid\SweetAlert\Facades\Alert;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Ambil item yang statusnya tidak 3
    $items = Item::with(['cat', 'rentals.cust'])->where('status', '!=', 3)->orderBy('name')->latest()->paginate(1000);

    // Ambil data rental yang statusnya 2
    $rentalData = Rental::with('cust')->where('status', '!=', 0)->get();

    // Struktur data rental dalam format array dengan item_id sebagai key
    $rentalMap = [];
    foreach ($rentalData as $rental) {
        foreach (json_decode($rental->item_id, true) as $itemId) {
            if (!isset($rentalMap[$itemId])) {
                $rentalMap[$itemId] = [];
            }
            $rentalMap[$itemId][] = [
                'customer_name' => $rental->cust->name,
                'date_start' => $rental->date_start,
                'date_end' => $rental->date_end,
            ];
        }
    }

    $title = 'Delete Item!';
    $text = "Are you sure you want to delete?";
    confirmDelete($title, $text);

    return view('admin.item.index', compact('items', 'rentalMap'));
}



    /**
     * Show the form for creating a new resource.
     */
    public function create($id = null)
    {
        $inject = [
            'url' => route('admin.item.store'),
            //'cat' => Category::pluck('name', 'id')->toArray(),

        ];
        if ($id){
            $item = Item::whereId($id)->first();
            $inject = [
                'url' => route('admin.item.update', $id),
                'item' => $item,
                //'cat' => Category::pluck('name', 'id')->toArray(),

            ];
        }
        $cat = Category::all();
        //return $cat;
        return view('admin.item.create', $inject, compact('cat'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'no_seri' => 'unique:items'
        ]);
        return $this->save($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Item::findOrFail($id);
    
        // Mengambil data rental dan menggabungkan dengan accessories
        $rental = Rental::leftJoin('accessories_categories as a', 'a.rental_id', '=', 'rentals.id')
            ->leftJoin('accessories as b', 'a.accessories_id', '=', 'b.id')
            ->select(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start',
                'rentals.date_end', 'rentals.status', DB::raw('GROUP_CONCAT(b.name) as access')
            )
            ->whereJsonContains('rentals.item_id', $id) // Menyaring berdasarkan item_id JSON
            ->groupBy(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start',
                'rentals.date_end', 'rentals.status'
            )
            ->get();
    
        // Format item_id sebagai JSON array dan hitung selisih hari
        foreach ($rental as $data) {
            $dateStart = Carbon::parse($data->date_start);
            $dateEnd = Carbon::parse($data->date_end);
            $daysDifference = $dateStart->diffInDays($dateEnd);
            $data->days_difference = $daysDifference;
    
            // Format item_id sebagai array JSON
            $data->item_id = json_encode(explode(',', $data->item_id));
        }
    
        return view('admin.item.show', compact('item', 'rental'));
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
        Item::whereId($id)->delete();
        Alert::success('Success', 'Delet Iteme Success');
        return back();
    }

    private function save(Request $request, $id = null)
    {
        // Validasi input
        $validate = $request->validate([
            'name'   => 'required',
            'cat_id' => 'required',
            'no_seri' => 'required',
           
        ]);

        // Temukan atau buat item baru
        $item = Item::updateOrCreate(['id' => $id], [
            'cat_id'  => $request->input('cat_id'),
            'name'    => $request->input('name'),
            'no_seri' => $request->input('no_seri')
        ]);

        // Penanganan gambar
        if ($request->hasFile('image')) {
            $newImages = [];
            
            // Handle new images
            foreach ($request->file('image') as $file) {
                $file_name = md5(now()->timestamp . $file->getClientOriginalName()) . '.jpg';
                
                try {
                    $img = ImageManagerStatic::make($file);
                    $img->resize(null, 600, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $img->save(public_path("images/item/{$file_name}"), 80, 'jpg');
                    $newImages[] = $file_name;
                } catch (\Exception $e) {
                    return back()->withErrors(['image' => 'Error processing the image: ' . $e->getMessage()])->withInput();
                }
            }
    
            // Combine old images with new ones
            $existingImages = json_decode($item->image, true) ?? [];
            $item->image = json_encode(array_merge($existingImages, $newImages));
        }

        // Simpan item
        $item->save();

        // Berikan notifikasi sukses dan redirect
        return redirect()->route('admin.item.index')->withSuccess('Upload Data Success');
    }
    public function mainten($id)
    {
        $item = Item::findOrFail($id);
        $item->status = 1;
        $item->save();
        Alert::success('Success', 'Item Maintenance Success');
        return redirect()->back();
    }

    public function sale()
    {
        $sale = ItemSale::with('item.cat')->get();
        return view('admin.item.sale', compact('sale'));
    }
    public function storesale(Request $request)
    {
        $request->validate([
            'descript' => 'required',
        ]);
        ItemSale::create($request->all());
        $sale = Item::find($request->input('item_id'));
        $sale->status = 3;
        $sale->save();
        Alert::success('Success', 'Maintenance has been add');
        return redirect()->back();
    }
    public function deleteImage(Request $request)
    {
        $image = $request->input('image');
        $item = Item::whereJsonContains('image', $image)->first();

        if ($item) {
            $images = json_decode($item->image);
            if (($key = array_search($image, $images)) !== false) {
                unset($images[$key]);
            }
            $item->image = json_encode(array_values($images));

            // Delete the actual file
            if (file_exists(public_path('images/item/' . $image))) {
                unlink(public_path('images/item/' . $image));
            }

            $item->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}
