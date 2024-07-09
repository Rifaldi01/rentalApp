<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cust = Customer::latest()->paginate();
        $title = 'Delete Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $customer = Customer::all();
        return view('manager.customer.index', compact('customer', ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id = null)
    {
        $inject = [
            'url' => route('manager.customer.store')
        ];
        if ($id){
            $customer = Customer::whereId($id)->first();
            $inject = [
                'url' => route('manager.customer.update', $id),
                'customer' => $customer
            ];
        }
        return view('manager.customer.create', $inject);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'no_identity' => 'unique:customers|min:11',
            'phone'       => 'unique:customers|min:11',
        ]);
        return $this->save($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $customer = Customer::findOrFail($id);
        $rental = $customer->rental()
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

        return view('manager.customer.show', compact('customer', 'rental'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return $this->create($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        return $this->save($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Customer::whereId($id)->delete();
        alert()->success('Succes','Customer Deleted Successfully');
        return back();
    }
    private function save(Request $request, $id = null)
    {
        $validate = $request->validate([
            'name'         => 'required',
            'no_identity' => 'required|numeric|min:10',
            'phone'       => 'required|numeric|min:10',
            'addres'      => 'required',
            'image' => $id ? 'nullable|image' : 'required|image',
        ]);
        $customer = Customer::firstOrNew(['id'=> $id]);
        $customer->name        = $request->input('name');
        $customer->no_identity = $request->input('no_identity');
        $customer->phone       = $request->input('phone');
        $customer->addres      = $request->input('addres');
        if ($request->hasFile('image')) {
            // If updating and a new image is uploaded, delete the old image
            if ($customer->image && file_exists(public_path('images/identity/' . $customer->image))) {
                unlink(public_path('images/identity/' . $customer->image));
            }

            // Store the new image
            $file = $request->file('image');
            $file_name = md5(now()) . '.' . $file->getClientOriginalExtension();
            $file->move('images/identity/', $file_name);
            $customer->image = $file_name;
        }
        $customer->save();
        Alert::success('Success', 'Upload Data Success');
        return redirect()->route('manager.customer.index')->withSuccess('Berhasil');
    }
}
