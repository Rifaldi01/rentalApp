<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $itemsByCategory = Item::with('cat')
            ->select('cat_id',
                \DB::raw('count(*) as total'),
                \DB::raw('SUM(case when status = 0 then 1 else 0 end) as available')
            )
            ->where('status', '!=', 3)
            ->groupBy('cat_id')
            ->get();
        $history = Rental::where('status', 0)->count();
        $rental = Rental::where('status', 1)->count();
        $problem = Rental::where('status', 2)->count();
        $maintenance = Item::where('cat_id', 1)->where('status', 1)->count();
        $item = Item::where('status', '!=', 3)->count();
        $customer = Customer::count();

        $today = Carbon::today();
        $threeDaysLater = $today->copy()->addDays(3);
        $jumlah = Rental::where('date_end', '<=', $threeDaysLater)->where('status', 1)->count();
        return view('employe.index', compact(
            'rental',
            'history',
            'problem',
            'maintenance',
            'item',
            'customer',
            'jumlah',
            'itemsByCategory'
        ));
    }
}
