<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemSale;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ItemSaleCortoller extends Controller
{
    public function index()
    {
        $sale = ItemSale::with('item.cat')->get();
        return view('superadmin.item.sale', compact('sale'));
    }
    public function store(Request $request)
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
}
