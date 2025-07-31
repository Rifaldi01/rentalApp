<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic;
use RealRashid\SweetAlert\Facades\Alert;
use ZipArchive;

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
        return view('admin.customer.index', compact('customer', ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id = null)
    {
        $inject = [
            'url' => route('admin.customer.store')
        ];
        if ($id){
            $customer = Customer::whereId($id)->first();
            $inject = [
                'url' => route('admin.customer.update', $id),
                'customer' => $customer
            ];
        }
        return view('admin.customer.create', $inject);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'no_identity' => 'unique:customers|min:11',
            'phone'       => 'unique:customers|min:9',
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
                DB::raw('GROUP_CONCAT(b.name) as access')
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

        return view('admin.customer.show', compact('customer', 'rental'));
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
        $validator = Validator::make($request->all(), [
            'name'         => 'required',
            'no_identity'  => 'required|numeric|min:10',
            'phone'        => 'required|numeric',
            'addres'       => 'required',
            'image'        => $id ? 'nullable|array' : 'required|array',
            'image.*'      => 'image',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $customer = Customer::firstOrNew(['id' => $id]);
        $customer->name = $request->input('name');
        if ($request->filled('no_identity') && $request->input('no_identity') != $customer->no_identity) {
            $customer->no_identity = $request->input('no_identity');
        }

        if ($request->filled('phone') && $request->input('phone') != $customer->phone) {
            $customer->phone = $request->input('phone');
        }

        $customer->addres = $request->input('addres');

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
                    $img->save(public_path("images/identity/{$file_name}"), 80, 'jpg');
                    $newImages[] = $file_name;
                } catch (\Exception $e) {
                    return back()->withErrors(['image' => 'Error processing the image: ' . $e->getMessage()])->withInput();
                }
            }

            // Combine old images with new ones
            $existingImages = json_decode($customer->image, true) ?? [];
            $customer->image = json_encode(array_merge($existingImages, $newImages));
        }

        $customer->save();

        return redirect()->route('admin.customer.index')->withSuccess('Berhasil');
    }

    public function downloadImages($id)
    {
        $customer = Customer::findOrFail($id);
        $images = json_decode($customer->image);

        if ($images && count($images) > 0) {
            $zip = new ZipArchive;
            $fileName = 'identity_images_' . $id . '.zip';
            $zipPath = public_path($fileName);

            // Membuka file zip
            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                foreach ($images as $image) {
                    $filePath = public_path('images/identity/' . $image);

                    // Periksa apakah file ada sebelum menambahkannya
                    if (file_exists($filePath)) {
                        $zip->addFile($filePath, $image);
                    } else {
                        // Tambahkan log error atau penanganan jika file tidak ditemukan
                        $zip->addFromString($image, "File not found: $filePath");
                    }
                }
                $zip->close();
            }

            return response()->download($zipPath)->deleteFileAfterSend(true);
        } else {
            return redirect()->back()->with('error', 'No images found.');
        }
    }
    public function deleteImage(Request $request)
    {
        $image = $request->input('image');
        $customer = Customer::whereJsonContains('image', $image)->first();

        if ($customer) {
            $images = json_decode($customer->image);
            if (($key = array_search($image, $images)) !== false) {
                unset($images[$key]);
            }
            $customer->image = json_encode(array_values($images));

            // Delete the actual file
            if (file_exists(public_path('images/identity/' . $image))) {
                unlink(public_path('images/identity/' . $image));
            }

            $customer->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

}
