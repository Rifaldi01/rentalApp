<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class DivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Delete Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $divisi = Divisi::all();
        return view('manager.divisi.index', compact('divisi'));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:divisis',

        ]);
        if ($validator->fails()) {
            Alert::error('Error', 'Create Category Failed!');
            return redirect()->route('manager.divisi.index')
                ->withErrors($validator)
                ->withInput();
        }
        Divisi::create($request->all());
        Alert::success('Success', 'Add Divisi Success');
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
        $post = Divisi::find($id);
        $post->update($request->all());

        return back()->withSuccess('Upload Data Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Divisi::whereId($id)->delete();
        alert()->success('Succes','Category Deleted Successfully');
        return back();
    }
}
