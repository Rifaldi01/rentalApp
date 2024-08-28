<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Html\Elements\Input;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cust = Service::latest()->paginate();
        $title = 'Delete Item!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $service = Service::where('status', 0)->get();

        return view('superadmin.service.index', compact('service'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id = null)
    {
        $inject = [
            'url' => route('superadmin.service.store')
        ];
        if ($id){
            $service = Service::whereId($id)->first();
            $inject = [
              'url' => route('superadmin.service.update', $id),
              'service' => $service
            ];
        }
        return view('superadmin.service.create', $inject);
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
        $request->validate([
            'date_finis' => 'required|date'
        ]);

        $service = Service::find($id);
        $service->date_finis =  $request->input('date_finis');
        $service->status = 1;
        $service->save();
        Alert::success('Finish', 'Service Has been Finshed');
        return back();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Service::whereId($id)->delete();
        Alert::success('Success','');
        return back();
    }

    private function save(Request $request, $id = null)
    {
        $validate = $request->validate([
            'name' => 'required',
            'phone' => 'required|numeric|min:11',
            'item' => 'required',
            'no_seri' => 'required',
            'date_service' => 'required',
            'jenis_service' => 'required',
            'nominal_in' => 'required|numeric',
            'nominal_out' => 'numeric',
            'diskon' => 'numeric',
            'biaya_ganti' => 'numeric',
            'ongkir' => 'numeric',
            'descript' => 'required',
        ]);
        $service = Service::firstOrNew(['id' => $id]);
        $service->name = $request->input('name');
        $service->phone = $request->input('phone');
        $service->name_sales = $request->input('name_sales');
        $service->phone_sales = $request->input('phone_sales');
        $service->item = $request->input('item');
        $service->no_seri = $request->input('no_seri');
        $service->descript = $request->input('descript');
        $service->type = $request->input('type');
        $service->nominal_in = $request->input('nominal_in');
        $service->nominal_out = $request->input('nominal_out');
        $service->diskon = $request->input('diskon');
        $service->biaya_ganti = $request->input('biaya_ganti');
        $service->ongkir = $request->input('ongkir');
        $service->date_service = $request->input('date_service');
        $service->jenis_service = $request->input('jenis_service');
        $service->accessories = $request->input('accessories');
        $service->save();
        Alert::success('Success', 'Upload Data Success');
        return redirect()->route('superadmin.service.index');
    }
    public function history()
    {
        $service = Service::all();
        return view('superadmin.service.index', compact('service'));
    }
}
