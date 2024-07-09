<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accessories;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AccessoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cust = Accessories::latest()->paginate();
        $title = 'Delete Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $acces = Accessories::all();
        return view('admin.accessories.index', compact('acces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'stok' => 'required|numeric',
        ]);
        Accessories::create($request->all());
        Alert::success('Success', 'Add Accessories Success');
        return back();
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);
        $post = Accessories::find($id);
        $post->update($request->all());
        Alert::success('Success', 'Edit Accessories Success');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Accessories::whereId($id)->delete();
        alert()->success('Succes','Customer Deleted Successfully');
        return back();
    }
}
