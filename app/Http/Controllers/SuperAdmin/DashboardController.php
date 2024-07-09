<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $itemsByCategory = Item::with('cat')
            ->select('cat_id',
                DB::raw('count(*) as total'),
                DB::raw('SUM(case when status = 0 then 1 else 0 end) as available')
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
        $rentals = Rental::leftjoin('accessories_categories as a', 'a.rental_id', '=', 'rentals.id')
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
            ->where('date_end', '<=', $threeDaysLater)
            ->where('status', 1)
            ->get();
        foreach ($rentals as $data) {
            $dateStart = Carbon::parse($data->date_start);
            $dateEnd = Carbon::parse($data->date_end);
            $daysDifference = $dateStart->diffInDays($dateEnd);
            $data->days_difference = $daysDifference;
        }
        $today = Carbon::today();
        $threeDaysLater = $today->copy()->addDays(3);
        $jumlah = Rental::where('date_end', '<=', $threeDaysLater)->where('status', 1)->count();
        return view('superadmin.index', compact(
            'rental',
            'history',
            'problem',
            'maintenance',
            'item',
            'customer',
            'rentals',
            'jumlah',
            'itemsByCategory'
        ));
    }
}
