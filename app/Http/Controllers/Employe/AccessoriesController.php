<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Models\Accessories;
use Illuminate\Http\Request;

class AccessoriesController extends Controller
{
    public function index()
    {
        $acces = Accessories::all();
        return view('employe.accessories.index', compact('acces'));
    }
}
