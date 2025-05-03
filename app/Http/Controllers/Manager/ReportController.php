<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Debts;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $currentYear = now()->year;

        $report = Rental::whereYear('rentals.created_at', $currentYear)
            ->with(['debt.bank', 'debt'])
            ->leftJoin('accessories_categories as a', 'a.rental_id', '=', 'rentals.id')
            ->leftJoin('accessories as b', 'a.accessories_id', '=', 'b.id')
            ->select(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start',
                'rentals.date_end', 'rentals.status', 'nominal_in', 'nominal_out', 'diskon', 'ongkir',
                'a.rental_id', 'rentals.created_at', 'rentals.no_inv', 'rentals.date_pays', 'rentals.tgl_inv', 'rentals.total_invoice',
                DB::raw('GROUP_CONCAT(DISTINCT b.name) as access'),
                DB::raw('nominal_in - diskon as total'),
                DB::raw('(nominal_in + nominal_out) as total_nominal')
            )
            ->groupBy(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start',
                'rentals.date_end', 'rentals.status', 'nominal_in', 'nominal_out', 'diskon', 'ongkir',
                'a.rental_id', 'rentals.created_at', 'rentals.no_inv', 'rentals.date_pays', 'rentals.tgl_inv', 'rentals.total_invoice'
            )
            ->orderBy('rentals.created_at', 'asc')
            ->get();

        $totaldiskon = $report->sum('diskon');
        $totalin = $report->sum('nominal_in');
        $totalincome = $report->sum(fn($item) => $item->nominal_in - $item->diskon);
        $totaloutside = $report->sum('nominal_out');

        $cicilan = Debts::whereYear('date_pay', $currentYear)
            ->with(['rental.cust', 'rental.item', 'bank'])
            ->get();

        $total = $cicilan->groupBy('id')->map(fn($group) => $group->sum(fn($item) => $item->pay_debts - ($item->rental->diskon ?? 0)));
        $uangmasuk = $cicilan->sum('pay_debts');
        $sisa = $cicilan->groupBy('id')->map(fn($group) => $group->sum(fn($item) => ($item->rental->total_invoice ?? 0) - $item->pay_debts));
        $diskon = $cicilan->sum(fn($item) => $item->rental->diskon ?? 0);
        $sisabayar = $cicilan->sum(fn($item) => $item->rental->nominal_out ?? 0);
        $totalbersih = $cicilan->sum(fn($item) => $item->pay_debts - ($item->rental->diskon ?? 0));

        return view('manager.report.index', compact(
            'sisabayar', 'totalbersih', 'diskon', 'sisa', 'totalin', 'report',
            'totaldiskon', 'totalincome', 'totaloutside', 'cicilan', 'total', 'uangmasuk'
        ));
    }

    public function filter(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|before_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:today',
        ]);

        $start_date = Carbon::parse($request->start_date)->toDateTimeString();
        $end_date = Carbon::parse($request->end_date)->toDateTimeString();

        $report = Rental::with(['debt.bank', 'debt'])
            ->leftJoin('accessories_categories as a', 'a.rental_id', '=', 'rentals.id')
            ->leftJoin('accessories as b', 'a.accessories_id', '=', 'b.id')
            ->select(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start',
                'rentals.date_end', 'rentals.status', 'nominal_in', 'nominal_out', 'diskon', 'ongkir',
                'a.rental_id', 'rentals.created_at', 'rentals.no_inv', 'rentals.date_pays', 'rentals.tgl_inv', 'rentals.total_invoice',
                DB::raw('GROUP_CONCAT(DISTINCT b.name) as access'),
                DB::raw('nominal_in - diskon as total'),
                DB::raw('(nominal_in + nominal_out) as total_nominal')
            )
            ->groupBy(
                'rentals.id', 'rentals.customer_id', 'rentals.item_id', 'rentals.name_company',
                'rentals.addres_company', 'rentals.phone_company', 'rentals.no_po', 'rentals.date_start',
                'rentals.date_end', 'rentals.status', 'nominal_in', 'nominal_out', 'diskon', 'ongkir',
                'a.rental_id', 'rentals.created_at', 'rentals.no_inv', 'rentals.date_pays', 'rentals.tgl_inv', 'rentals.total_invoice'
            )
            ->whereBetween('rentals.created_at', [$start_date, $end_date])
            ->orderBy('rentals.created_at', 'asc')
            ->get();

        $totaldiskon = $report->sum('diskon');
        $totalin = $report->sum('nominal_in');
        $totalincome = $report->sum(fn($item) => $item->nominal_in - $item->diskon);
        $totaloutside = $report->sum('nominal_out');

        $cicilan = Debts::with(['rental.cust', 'rental.item', 'bank'])->get();
        $total = $cicilan->groupBy('id')->map(fn($group) => $group->sum(fn($item) => $item->pay_debts - ($item->rental->diskon ?? 0)));
        $uangmasuk = $cicilan->sum('pay_debts');
        $sisa = $cicilan->groupBy('id')->map(fn($group) => $group->sum(fn($item) => ($item->rental->total_invoice ?? 0) - $item->pay_debts));
        $diskon = $cicilan->sum(fn($item) => $item->rental->diskon ?? 0);
        $sisabayar = $cicilan->sum(fn($item) => $item->rental->nominal_out ?? 0);
        $totalbersih = $cicilan->sum(fn($item) => $item->pay_debts - ($item->rental->diskon ?? 0));

        return view('manager.report.index', compact(
            'sisabayar', 'totalbersih', 'diskon', 'sisa', 'totalin', 'report',
            'totaldiskon', 'totalincome', 'totaloutside', 'cicilan', 'total', 'uangmasuk'
        ));
    }

    public function filtercicilan(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date|before_or_equal:today',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai|before_or_equal:today',
        ], [
            'tanggal_akhir.after_or_equal' => 'Tanggal Akhir Tidak Boleh Kurang Dari Tanggal Mulai',
            'tanggal_mulai.before_or_equal' => 'Tanggal Mulai Harus Sebelum Atau Sama Dengan Hari Ini',
            'tanggal_akhir.before_or_equal' => 'Tanggal Akhir Harus Sebelum Atau Sama Dengan Hari Ini',
        ]);

        $tanggal_mulai = Carbon::parse($request->tanggal_mulai)->toDateTimeString();
        $tanggal_akhir = Carbon::parse($request->tanggal_akhir)->toDateTimeString();

        $cicilan = Debts::with(['rental.cust', 'rental.item', 'bank'])
            ->whereBetween('date_pay', [$tanggal_mulai, $tanggal_akhir])
            ->get();

        $uangmasuk = $cicilan->sum('pay_debts');
        $total = $cicilan->groupBy('id')->map(fn($group) => $group->sum(fn($item) => $item->pay_debts - ($item->rental->diskon ?? 0)));
        $sisa = $cicilan->groupBy('id')->map(fn($group) => $group->sum(fn($item) => ($item->rental->total_invoice ?? 0) - $item->pay_debts));
        $diskon = $cicilan->sum(fn($item) => $item->rental->diskon ?? 0);
        $sisabayar = $cicilan->sum(fn($item) => $item->rental->nominal_out ?? 0);
        $totalbersih = $cicilan->sum(fn($item) => $item->pay_debts - ($item->rental->diskon ?? 0));

        return view('manager.report.index', compact(
            'sisabayar', 'totalbersih', 'diskon', 'sisa', 'uangmasuk', 'cicilan', 'total'
        ));
    }
}
