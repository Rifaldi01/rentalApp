<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Rental;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function rental(Request $request)
    {
        $title = 'Delete Rental?';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        // Dropdown Tahun
        $listTahun = Rental::selectRaw('YEAR(tgl_inv) as thn')
            ->distinct()
            ->orderBy('thn', 'DESC')
            ->get();

        $tahun = $request->tahun;
        $bulan = $request->bulan;

        $rentals = Rental::leftJoin('accessories_categories as a', 'a.rental_id', '=', 'rentals.id')
            ->leftJoin('accessories as b', 'a.accessories_id', '=', 'b.id')
            ->select(
                'rentals.id',
                'rentals.customer_id',
                'rentals.item_id',
                'rentals.name_company',
                'rentals.addres_company',
                'rentals.phone_company',
                'rentals.no_po',
                'rentals.date_start',
                'date_pays',
                'rentals.date_end',
                'rentals.status',
                'a.rental_id',
                'nominal_in',
                'nominal_out',
                'diskon',
                'ongkir',
                'rentals.image',
                'rentals.created_at',
                'no_inv',
                'rentals.deleted_at',
                'rentals.keterangan_item',
                'rentals.keterangan_acces',
                'rentals.fee',
                'rentals.tgl_inv',
                'rentals.updated_at',
                'rentals.total_invoice',
                'ppn',
                DB::raw('GROUP_CONCAT(b.name) as access')
            );

        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        // Pertama kali membuka halaman
        if (!$request->has('tahun') && !$request->has('bulan')) {

            $rentals->whereYear('rentals.tgl_inv', now()->year)
                ->whereMonth('rentals.tgl_inv', now()->month);

        } else {

            // Tahun
            if (!empty($tahun) && $tahun != 'all') {
                $rentals->whereYear('rentals.tgl_inv', $tahun);
            }

            // Bulan
            if (!empty($bulan) && $bulan != 'all') {
                $rentals->whereMonth('rentals.tgl_inv', $bulan);
            }
        }

        $rentals = $rentals
            ->groupBy(
                'rentals.id',
                'rentals.customer_id',
                'rentals.item_id',
                'rentals.name_company',
                'rentals.addres_company',
                'rentals.phone_company',
                'rentals.no_po',
                'rentals.date_start',
                'date_pays',
                'rentals.date_end',
                'rentals.status',
                'a.rental_id',
                'nominal_in',
                'nominal_out',
                'diskon',
                'ongkir',
                'rentals.image',
                'rentals.created_at',
                'no_inv',
                'rentals.deleted_at',
                'rentals.keterangan_item',
                'rentals.keterangan_acces',
                'rentals.fee',
                'rentals.tgl_inv',
                'rentals.updated_at',
                'rentals.total_invoice',
                'ppn'
            )
            ->orderBy('rentals.tgl_inv', 'DESC')
            ->get();

        return view('marketing.rental', compact(
            'rentals',
            'listTahun',
            'tahun',
            'bulan'
        ));
    }
    public function service(Request $request)
    {
        $tahun = $request->input('tahun', now()->year);
        $bulan = $request->input('bulan', now()->month);

        $service = Service::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->get();

        $bank = Bank::all();

        return view('marketing.service', compact(
            'service',
            'bank',
            'tahun',
            'bulan'
        ));
    }

}
