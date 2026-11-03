<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Maintenance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MaintenanceController extends Controller
{
    public function index()
    {
        $mainten = Maintenance::with('item.cat')->where('status',0)->get();
        return view('superadmin.maintenance.index', compact('mainten'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'descript' => 'required',
        ]);
        Maintenance::create($request->all());
        $mainten = Item::find($request->input('item_id'));
        $mainten->status = 1;
        $mainten->save();
        Alert::success('Success', 'Maintenance has been add');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $destroy = Maintenance::find($id);
        $destroy->status = 1;
        $destroy->save();
        $mainten = Item::findOrFail($destroy->item_id);
        $mainten->status = 0;
        $mainten->save();

        Alert::success('Sucess', 'Maintenance has been finish');
        return back();
    }


}
