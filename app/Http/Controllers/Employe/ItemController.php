<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $item = Item::with('cat')->where('status', '!=', 3)->orderBy('name')->get();
        return view('employe.item.index', compact('item'));
    }
    public function sale()
    {
        $sale = Item::where('status', '3')->get();
        return view('superadmin.item.sale', compact('sale'));
    }
}
