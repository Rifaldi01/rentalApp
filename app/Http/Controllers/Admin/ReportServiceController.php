<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DebtServic;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportServiceController extends Controller
{
    public function index()
    {
        // Ambil tahun saat ini
        $currentYear = now()->year;

        // Filter data berdasarkan tahun saat ini
        $report = DebtServic::with('service.cust')
            ->whereYear('date_pay', $currentYear)
            ->orderBy('date_pay', 'asc')
            ->get();

        // Hitung total berdasarkan data yang difilter
        $totalincome = $report->sum(function ($item) {
            return optional($item->service)->nominal_in - optional($item->service)->diskon - optional($item->service)->biaya_ganti;
        });

        $totaldiskon = $report
            ->groupBy('service_id')
            ->sum(function ($items) {
                return $items->first()->service->diskon ?? 0;
            });

        $totalbiaya = $report
            ->groupBy('service_id')
            ->sum(function ($items) {
                return $items->first()->service->biaya_ganti ?? 0;
            });
        $totalppn = $report
            ->groupBy('service_id')
            ->sum(function ($items) {
                return $items->first()->service->ppn ?? 0;
            });

        $totalin = $report->sum(function ($item) {
            return $item->pay_debts ?? 0;
        });

        $totaloutside = $report
            ->groupBy('service_id')
            ->sum(function ($items) {
                return $items->first()->service->nominal_out ?? 0;
            });

        $total = $this->calculateCicilan($report);

        return view('admin.reportservice.index', compact(
            'totalppn',
            'totalin',
            'totalbiaya',
            'report',
            'totalincome',
            'totaloutside',
            'totaldiskon',
            'total'
        ));
    }

    public function filter(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|before_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:today',
        ], [
            'end_date.after_or_equal' => 'Tanggal Akhir Tidak Boleh Kurang Dari Tanggal Mulai',
            'start_date.before_or_equal' => 'Tanggal Mulai Harus Tanggal Sebelum Atau Sama Dengan Hari Ini',
            'end_date.before_or_equal' => 'Tanggal Akhir Harus Tanggal Sebelum Atau Sama Dengan Hari Ini',
        ]);
        $start_date = Carbon::parse(request()->start_date)->toDateTimeString();
        $end_date = Carbon::parse(request()->end_date)->toDateTimeString();
        $report = DebtServic::with('service.cust', 'bank')
            ->whereBetween('date_pay', [$start_date, $end_date])
            ->orderBy('date_pay')
            ->get();
        $totalincome = $report
            ->groupBy('service_id')
            ->sum(function ($items) {

                $service = optional($items->first())->service;

                return ($service->nominal_in ?? 0)
                    - ($service->diskon ?? 0)
                    - ($service->biaya_ganti ?? 0);
            });

        $totaldiskon = $report
            ->groupBy('service_id')
            ->sum(function ($items) {
                return $items->first()->service->diskon ?? 0;
            });

        $totalbiaya = $report
            ->groupBy('service_id')
            ->sum(function ($items) {
                return $items->first()->service->biaya_ganti ?? 0;
            });
        $totalppn = $report
            ->groupBy('service_id')
            ->sum(function ($items) {
                return $items->first()->service->ppn ?? 0;
            });

        $totalin = $report->sum(function ($item) {
            return $item->pay_debts ?? 0;
        });

        $total = $this->calculateCicilan($report);
        $totaloutside = $report
            ->groupBy('service_id')
            ->sum(function ($items) {
                return $items->first()->service->nominal_out ?? 0;
            });
        return view('admin.reportservice.index', compact(
            'totalppn',
            'report',
            'totalbiaya',
            'totalin',
            'totalincome',
            'totaloutside',
            'totaldiskon',
            'total'
        ));
    }
    private function calculateCicilan($report)
    {
        $total = [];

        $grouped = $report
            ->sortBy([['service_id','asc'],['date_pay','asc']])
            ->groupBy('service_id');

        foreach ($grouped as $serviceId => $items) {

            $first = true;

            foreach ($items as $item) {

                $ppn = 0;
                $diskon = 0;
                $biayaGanti = 0;

                if ($first) {
                    $ppn = $item->service->ppn ?? 0;
                    $diskon = $item->service->diskon ?? 0;
                    $biayaGanti = $item->service->biaya_ganti ?? 0;
                    $first = false;
                }

                $total[$item->id] = $item->pay_debts - $ppn - $diskon - $biayaGanti;
            }
        }

        return $total;
    }

}
