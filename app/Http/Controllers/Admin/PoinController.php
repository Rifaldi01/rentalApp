<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;

class PoinController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $poin = Rental::with('cust', 'access.accessoriescategory', 'item')->get();
            return response()->json(['data' => $poin]);
        }
        return view('admin.poin.index');
    }

}
