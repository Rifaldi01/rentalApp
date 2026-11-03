<?php

use App\Http\Controllers\SuperAdmin\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\CustomerController;
use App\Http\Controllers\SuperAdmin\AccessoriesController;
use App\Http\Controllers\SuperAdmin\CategoryController;
use App\Http\Controllers\SuperAdmin\ItemController;
use App\Http\Controllers\SuperAdmin\MaintenanceController;
use App\Http\Controllers\SuperAdmin\RentalController;
use App\Http\Controllers\SuperAdmin\HistoryController;
use App\Http\Controllers\SuperAdmin\ProblemController;
use App\Http\Controllers\EditController;
use App\Http\Controllers\SuperAdmin\ReportMaintenController;
use App\Http\Controllers\SuperAdmin\ReportProblemController;
use App\Http\Controllers\SuperAdmin\CatAccessController;
use App\Http\Controllers\SuperAdmin\ServiceController;
use App\Http\Controllers\SuperAdmin\ReportServiceController;
use App\Http\Controllers\SuperAdmin\ItemSaleCortoller;
use App\Http\Controllers\SuperAdmin\AccountController;

Route::group(['middleware' => ['auth:web', 'role:superAdmin'], 'prefix' => 'superadmin'], function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index']);

    //csutomer
    Route::resource('/customer', CustomerController::class)->names('superadmin.customer');
    //customer end

    //accessories
    Route::resource('/accessories', AccessoriesController::class)->names('superadmin.acces');
    //accessories end

    //item
    Route::resource('/item', ItemController::class)->names('superadmin.item');
    Route::get('/items/sale', [ItemSaleCortoller::class, 'index'])->name('sale');
    Route::resource('/category', CategoryController::class)->names('superadmin.cat');
    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('mainten.index');
    Route::post('/maintenance/{id}/item', [MaintenanceController::class, 'destroy'])->name('mainten.item');
    Route::post('/maintenance/', [MaintenanceController::class, 'store'])->name('mainten.store');
    Route::post('/sale/', [ItemSaleCortoller::class, 'store'])->name('item.sale');
    //item end

    //rental
    Route::resource('/rental',RentalController::class)->names('superadmin.rental');
    Route::get('/finis', [RentalController::class, 'index'])->name('finis.index');
    Route::post('/finis/{id}', [RentalController::class, 'finis'])->name('rental.finis');
    Route::post('/problem/{id}/rental', [RentalController::class, 'problem'])->name('rental.problem');
    Route::get('/history', [HistoryController::class, 'history'])->name('rental.history');
    Route::get('/problems', [ProblemController::class, 'index'])->name('rental.problems');
    Route::post('/problems/{id}/finis', [ProblemController::class, 'destroy'])->name('problem.finis');
    Route::post('/problems/{id}/returned', [ProblemController::class, 'returned'])->name('problem.returned');
    Route::post('/problem/', [ProblemController::class, 'store'])->name('problem.store');
    //rental end

    //service
    Route::resource('/service', ServiceController::class)->names('superadmin.service');
    Route::get('/history/service', [ServiceController::class, 'history'])->name('superadmin.service.history');
    //service end

    //report
    Route::get('/report', [ReportController::class, 'index'])->name('rental.report');
    Route::get('/report/filter', [ReportController::class, 'filter'])->name('report.filter');
    Route::get('/mainten/report', [ReportMaintenController::class, 'index'])->name('mainten.report');
    Route::get('/mainten/report/filter', [ReportMaintenController::class, 'filter'])->name('mainten.filter');
    Route::get('/problem/report', [ReportProblemController::class, 'index'])->name('problem.report');
    Route::get('/problem/report/filter', [ReportProblemController::class, 'filter'])->name('problem.filter');
    Route::get('report/service', [ReportServiceController::class, 'index'])->name('report.service.index');
    Route::get('/report/service/filter', [ReportServiceController::class, 'filter'])->name('service.filter');
    //report end

    //account
    Route::resource('/account', AccountController::class)->names('superadmin.account');
    //account end
});
