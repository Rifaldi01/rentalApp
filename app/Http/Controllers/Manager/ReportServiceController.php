<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\DebtServic;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportServiceController extends Controller
{
    public function index()
    {
        $currentYear = now()->year;

        $report = DebtServic::with(['service', 'bank'])
            ->whereYear('date_pay', $currentYear)
            ->orderBy('date_pay', 'asc')
            ->get();

        // Ambil Service yang unik agar tidak dihitung berkali-kali
        $services = $report->pluck('service')
            ->filter()
            ->unique('id');

        // Total uang masuk dari pembayaran
        $totalin = $report->sum('pay_debts');

        // Total dari service (1x per invoice)
        $totalbiaya = $services->sum(function ($service) {
            return $service->biaya_ganti ?? 0;
        });

        $totaldiskon = $services->sum(function ($service) {
            return $service->diskon ?? 0;
        });

        $totaloutside = $services->sum(function ($service) {
            return $service->nominal_out ?? 0;
        });

        // Grand Total
        $totalincome = $totalin - $totalbiaya - $totaldiskon;

        return view('manager.reportservice.index', compact(
            'report',
            'totalin',
            'totalbiaya',
            'totaldiskon',
            'totaloutside',
            'totalincome'
        ));
    }

    public function filter(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|before_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date|before_or_equal:today',
        ], [
            'end_date.after_or_equal' => 'Tanggal Akhir Tidak Boleh Kurang Dari Tanggal Mulai',
            'start_date.before_or_equal' => 'Tanggal Mulai Harus Tanggal Sebelum Atau Sama Dengan Hari Ini',
            'end_date.before_or_equal' => 'Tanggal Akhir Harus Tanggal Sebelum Atau Sama Dengan Hari Ini',
        ]);

        $start = Carbon::parse($request->start_date)->startOfDay();
        $end   = Carbon::parse($request->end_date)->endOfDay();

        $report = DebtServic::with(['service', 'bank'])
            ->whereBetween('date_pay', [$start, $end])
            ->orderBy('date_pay', 'asc')
            ->get();

        // Ambil Service yang unik agar tidak double
        $services = $report->pluck('service')
            ->filter()
            ->unique('id');

        // Total pembayaran
        $totalin = $report->sum('pay_debts');

        // Total biaya ganti
        $totalbiaya = $services->sum(function ($service) {
            return $service->biaya_ganti ?? 0;
        });

        // Total diskon
        $totaldiskon = $services->sum(function ($service) {
            return $service->diskon ?? 0;
        });

        // Total belum bayar
        $totaloutside = $services->sum(function ($service) {
            return $service->nominal_out ?? 0;
        });

        // Grand total
        $totalincome = $totalin - $totalbiaya - $totaldiskon;

        return view('manager.reportservice.index', compact(
            'report',
            'totalin',
            'totalbiaya',
            'totaldiskon',
            'totaloutside',
            'totalincome'
        ));
    }
}
