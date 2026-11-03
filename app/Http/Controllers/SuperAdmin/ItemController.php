<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cust = Item::latest()->paginate();
        $title = 'Delete Item!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $item = Item::with('cat')->where('status', '!=', 3)->orderBy('name')->get();
        return view('superadmin.item.index', compact('item'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id = null)
    {
        $inject = [
            'url' => route('superadmin.item.store'),
            //'cat' => Category::pluck('name', 'id')->toArray(),

        ];
        if ($id){
            $item = Item::whereId($id)->first();
            $inject = [
              'url' => route('superadmin.item.update', $id),
              'item' => $item,
              //'cat' => Category::pluck('name', 'id')->toArray(),

            ];
        }
        $cat = Category::all();
        //return $cat;
        return view('superadmin.item.create', $inject, compact('cat'));
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
        $rental = $item->rental()
            ->leftjoin('accessories_categories as a', 'a.rental_id', '=', 'rentals.id')
            ->leftjoin('accessories as b', 'a.accessories_id', '=', 'b.id')
            ->select(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po','rentals.date_start',
                'rentals.date_end', 'rentals.status', 'a.rental_id',
                \DB::raw('GROUP_CONCAT(b.name) as access')
            )
            ->groupBy(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start',
                'rentals.date_end', 'rentals.status', 'a.rental_id'
            )
            ->get();

        foreach ($rental as $data) {
            $dateStart = Carbon::parse($data->date_start);
            $dateEnd = Carbon::parse($data->date_end);
            $daysDifference = $dateStart->diffInDays($dateEnd);
            $data->days_difference = $daysDifference;
        }
        return view('superadmin.item.show', compact('item', 'rental'));
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
        $validate = $request->validate([
            'name'=> 'required',
            'cat_id'=> 'required',
            'no_seri'=> 'required'
        ]);
        $item = Item::firstOrNew(['id' => $id]);
        $item->cat_id  = $request->input('cat_id');
        $item->name    = $request->input('name');
        $item->no_seri = $request->input('no_seri');
        if ($request->file('image')){
            $file = $request->file('image');
            $file_name = md5(now()).'.'.$file->getClientOriginalExtension();
            $file->move('images/item/',$file_name);
            $item->image = $file_name;
        }else{
            $item->save();
        }
        $item->save();
        Alert::success('Success', 'Upload Data Success');
        return redirect()->route('superadmin.item.index');
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
        $sale = Item::where('status', '3')->get();
        return view('superadmin.item.sale', compact('sale'));
    }
    public function itemsale($id)
    {
        $item = Item::findOrFail($id);
        $item->status = 3;
        $item->save();
        Alert::success('Success', 'Item Maintenance Success');
        return redirect()->back();
    }
}
