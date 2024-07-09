<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        $mainten = Maintenance::with('item.cat')->where('status',0)->get();
        return view('employe.maintenance.index', compact('mainten'));
    }
}
