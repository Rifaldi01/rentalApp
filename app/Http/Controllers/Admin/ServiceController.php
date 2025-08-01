<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Debts;
use App\Models\DebtServic;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

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
        $service = Service::where('status', 0)
        ->orWhere('nominal_out', '!=', 0)
        ->get();
        $bank = Bank::all();
        return view('admin.service.index', compact('service', 'bank'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id = null)
    {
        $inject = [
            'url' => route('admin.service.store')
        ];
        if ($id){
            $bank = Bank::pluck('name', 'id')->toArray();
            $debt = DebtServic::all();
            $service = Service::whereId($id)->first();
            $inject = [
                'url' => route('admin.service.update', $id),
                'debt' => $debt,
                'bank' => $bank,
                'service' => $service
            ];
        }
        return view('admin.service.create', $inject, compact('bank'));
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
            'date_finis' => 'required|date',
        ]);

        $service = Service::find($id);

        // Simpan nilai biaya_ganti sebelumnya
        $previous_biaya_ganti = $service->biaya_ganti;

        // Perbarui kolom service
        $service->date_finis = $request->input('date_finis');
        $service->descript = $request->input('descript');
        $service->biaya_ganti = $request->input('biaya_ganti');
        $service->status = 1;

        // Cek apakah biaya_ganti berubah
        if ($previous_biaya_ganti != $service->biaya_ganti) {
            $difference = $service->biaya_ganti - $previous_biaya_ganti;

            // Update total_invoice dan nominal_out dengan selisih
            $service->total_invoice += $difference;
            $service->nominal_out += $difference;
        }

        $service->save();

        Alert::success('Finish', 'Service Has been Finished');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Hapus semua debt service yang berelasi dengan service
        DebtServic::where('service_id', $id)->delete();

        // Hapus service
        Service::whereId($id)->delete();

        Alert::success('Success', 'Service dan data hutangnya berhasil dihapus.');
        return back();
    }

    private function save(Request $request, $id = null)
    {
        $validate = $request->validate([
            'name'          => 'required',
            'item'          => 'required',
            'no_seri'       => 'required',
            'date_service'  => 'required',
            'jenis_service' => 'required',
            'nominal_in'    => 'required|numeric',
            'nominal_out'   => 'numeric',
            'diskon'        => 'numeric',
            'biaya_ganti'   => 'numeric',
            'ongkir'        => 'numeric',
            'type'          => 'required',
            'no_inv'        => 'required',
            'tgl_inv'       => 'required',
            'total_invoice' => 'required|numeric',
        ],[
            'name.required'          => 'Nama Pelanggan Wajib Diisi',
            'item.required'          => 'Item Wajib Diisi',
            'no_seri.required'       => 'No Seri Wajib Diisi',
            'date_service.required'  => 'Tanggal Service Wajib Diisi',
            'jenis_service.required' => 'Jenis Service Wajib Diisi',
            'nominal_in.required'    => 'Uang Masuk Wajib Diisi',
            'nominal_in.numeric'     => 'Uang masuk Harus Berupa Angka',
            'nominal_out.numeric'    => 'Sisa Pembayaran Harus Berupa Angka',
            'diskon.numeric'         => 'Diskon Harus Berupa Angka',
            'biaya_ganti.numeric'    => 'Ongkir Harus Berupa Angka',
            'ongkir.numeric'         => 'Ongkir Harus Berupa Angka',
            'type.required'          => 'Type Wajib Diisi',
            'no_inv.required'        => 'No Invoice Wajib Diisi',
            'tgl_inv.required'       => 'Tanggal Invoice Wajib Diisi',
            'total_invoice.required' => 'Total Invoice Wajib Diisi',
        ]);
        $service = Service::firstOrNew(['id' => $id]);
        $service->name          = $request->input('name');
        $service->phone         = $request->input('phone');
        $service->item          = $request->input('item');
        $service->no_seri       = $request->input('no_seri');
        $service->descript      = $request->input('descript');
        $service->type          = $request->input('type');
        $service->nominal_in    = $request->input('nominal_in');
        $service->nominal_out   = $request->input('nominal_out');
        $service->diskon        = $request->input('diskon');
        $service->biaya_ganti   = $request->input('biaya_ganti');
        $service->ongkir        = $request->input('ongkir');
        $service->date_service  = $request->input('date_service');
        $service->jenis_service = $request->input('jenis_service');
        $service->no_inv        = $request->input('no_inv');
        $service->total_invoice = $request->input('total_invoice');
        $service->tgl_inv       = $request->input('tgl_inv');
        $service->ppn           = $request->input('ppn');
        $service->save();

        $debts = DebtServic::create([
            'service_id' => $service->id,
            'bank_id'    => $request->input('bank_id'),
            'pay_debts'  => $service->nominal_in,
            'penerima'   => $request->input('penerima'),
            'date_pay'   => $request->input('date_pay'),
            'description'=> $request->input('description'),
        ]);

        return redirect()->route('admin.service.index')->withSuccess('Upload Data Success');
    }
    public function bayar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nominal_in'   => 'required',
            'pay_debts'    => 'required',
            'date_pay'     => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Format ulang input nominal_in dan pay_debts untuk menghapus simbol dan titik
        $nominal_in = (int) str_replace(['Rp.', '.', ' '], '', $request->input('nominal_in'));
        $biaya_ganti = (int) str_replace(['Rp.', '.', ' '], '', $request->input('biaya_ganti'));
        $pay_debts = (int) str_replace(['Rp.', '.', ' '], '', $request->input('pay_debts'));

        // Update nominal_in dan nominal_out di tabel services
        $service = Service::findOrFail($id);

        // Cek apakah biaya_ganti berubah
        if ($biaya_ganti != $service->biaya_ganti) {
            $service->total_invoice += $biaya_ganti - $service->biaya_ganti; // Update total_invoice hanya jika biaya_ganti berubah
            $service->nominal_out += $biaya_ganti - $service->biaya_ganti;  // Update nominal_out hanya jika biaya_ganti berubah
        }

        // Kurangi nominal_out dengan pay_debts
        $service->nominal_out -= $pay_debts;

        // Set nominal_in yang baru
        $service->nominal_in = $nominal_in;
        $service->biaya_ganti = $biaya_ganti;
        $service->save();

        // Simpan data ke tabel debts
        DebtServic::create([
            'service_id'  => $id,
            'bank_id'    => $request->input('bank_id'),
            'pay_debts'  => $pay_debts,
            'date_pay'   => $request->input('date_pay'),
            'penerima'   => $request->input('penerima'),
            'description'=> $request->input('description'),
        ]);

        return back()->withSuccess('Pembayaran Berhasil');
    }


    public function history()
    {
        $service = Service::all();
        $bank = Bank::all();
        return view('admin.service.index', compact('service', 'bank'));
    }
    public function invoice(Request $request, string $id)
    {
        $request->validate([
            'tgl_inv' => 'required|date',
            'total_invoice' => 'required',
            'tgl_inv' => $id ? 'nullable' : 'required',
        ]);

        $service = Service::find($id);

        $total_invoice = (int) str_replace(['Rp.', '.', ' '], '', $request->input('total_invoice'));

        $service->tgl_inv = $request->input('tgl_inv');
        $service->no_inv = $request->input('no_inv');
        $service->total_invoice = $total_invoice;
        $service->nominal_out = $total_invoice;

        $service->save();


        return back()->withSuccess('Invoice Berhasil Diubah');
    }
    public function invoices(Request $request, string $id)
    {
        $request->validate([
            'total_invoice' => 'required',
            'tgl_inv' => $id ? 'nullable' : 'required',
        ]);

        $service = Service::find($id);

        $total_invoice = (int) str_replace(['Rp.', '.', ' '], '', $request->input('total_invoice'));

        $service->tgl_inv = $request->input('tgl_inv');
        $service->no_inv = $request->input('no_inv');
        $service->total_invoice = $total_invoice;
        $service->save();


        return back()->withSuccess('Invoice Berhasil Diubah');
    }
    public function finis($id)
    {
        // Temukan objek rental berdasarkan ID
        $service = Service::findOrFail($id);
        $service->nominal_out = 0;
        $service->status = 1;
        $service->save();

        return redirect()->back()->with('success', 'Rental Finished');
    }
}
