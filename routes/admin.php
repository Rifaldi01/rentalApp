<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RentalController;
use App\Http\Controllers\Admin\ProblemController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AccessoriesController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReportMaintenController;
use App\Http\Controllers\Admin\ReportProblemController;
use App\Http\Controllers\Admin\ReportServiceController;
use App\Models\Service;

Route::group(['middleware' => ['auth:web', 'role:admin'], 'prefix' => 'admin'], function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index']);

    //rental start
    Route::resource('/rental', RentalController::class)->names('admin.rental');
    Route::get('/hsty/rental', [RentalController::class, 'hsty'])->name('admin.rental.history');
    Route::post('/finis/{id}', [RentalController::class, 'finis'])->name('admin.rental.finis');
    Route::post('/problem/{id}/rental', [RentalController::class, 'problem'])->name('admin.rental.problem');
    Route::post('/rental/deleteimage', [RentalController::class, 'deleteImage'])->name('admin.rental.deleteImage');
    Route::get('/rental/download/{id}', [RentalController::class, 'downloadImages'])->name('admin.rental.downloadImages');
    Route::put('/rental/date/{id}', [RentalController::class, 'tanggalBuat'])->name('admin.rental.tanggalbuat');
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('admin.pembayaran.index');
    Route::put('/pembayaran/{id}', [PembayaranController::class, 'bayar'])->name('admin.pembayaran.bayar');
    Route::put('/pembayaran/edit/{id}', [PembayaranController::class, 'update'])->name('admin.update.totalinv');
    Route::get('/pembayaran/filter', [PembayaranController::class, 'filter'])->name('admin.pembayaran.filter');
    //rental end

    //problem
    Route::get('/problems', [ProblemController::class, 'index'])->name('admin.rental.problems');
    Route::post('/problems/{id}/finis', [ProblemController::class, 'destroy'])->name('admin.problem.finis');
    Route::post('/problems/{id}/returned', [ProblemController::class, 'returned'])->name('admin.problem.returned');
    Route::post('/problem/', [ProblemController::class, 'store'])->name('admin.problem.store');
    //problem end

    //customer
    Route::resource('/customer', CustomerController::class)->names('admin.customer');
    Route::get('/customer/download/{id}', [CustomerController::class, 'downloadImages'])->name('admin.customer.downloadImages');
    Route::post('/customer/deleteimage', [CustomerController::class, 'deleteImage'])->name('admin.customer.deleteImage');

    //customer end

    //item
    Route::resource('/item', ItemController::class)->names('admin.item');
    Route::post('/items/{id}/mainten', [ItemController::class, 'mainten'])->name('items.mainten');
    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('admin.mainten.index');
    Route::post('/maintenance/{id}/item', [MaintenanceController::class, 'destroy'])->name('admin.mainten.item');
    Route::post('/maintenance/', [MaintenanceController::class, 'store'])->name('admin.mainten.store');
    Route::get('/items/sale', [ItemController::class, 'sale'])->name('admin.sale');
    Route::post('/sale/', [ItemController::class, 'storesale'])->name('admin.item.sale');
    Route::post('/item/deleteimage', [ItemController::class, 'deleteImage'])->name('admin.item.deleteImage');
    Route::post('/item/rent/{id}', [ItemController::class, 'rentItm'])->name('admin.item.rentItm');
    //item end

    //category
    Route::resource('/category', CategoryController::class)->names('admin.cat');
    //category end

    //accessories
    Route::resource('/accessories', AccessoriesController::class)->names('admin.acces');
    //accessories end

    //service
    Route::resource('/service', ServiceController::class)->names('admin.service');
    Route::put('/invoice/edit/{id}', [ServiceController::class, 'invoice'])->name('admin.service.invoice');
    Route::put('/invoices/edit/{id}', [ServiceController::class, 'invoices'])->name('admin.service.invoices');
    Route::get('/history/service', [ServiceController::class, 'history'])->name('admin.service.history');
    Route::put('/pembayaran/service/{id}', [ServiceController::class, 'bayar'])->name('admin.service.bayar');
    //service end

    //report
    Route::get('/report', [ReportController::class, 'index'])->name('admin.rental.report');
    Route::get('/report/filter', [ReportController::class, 'filter'])->name('admin.report.filter');
    Route::get('/mainten/report', [ReportMaintenController::class, 'index'])->name('admin.mainten.report');
    Route::get('/mainten/report/filter', [ReportMaintenController::class, 'filter'])->name('admin.mainten.filter');
    Route::get('/problem/report', [ReportProblemController::class, 'index'])->name('admin.problem.report');
    Route::get('/problem/report/filter', [ReportProblemController::class, 'filter'])->name('admin.problem.filter');
    Route::get('report/service', [ReportServiceController::class, 'index'])->name('admin.report.service.index');
    Route::get('/report/service/filter', [ReportServiceController::class, 'filter'])->name('admin.service.filter');
    //report end
});


