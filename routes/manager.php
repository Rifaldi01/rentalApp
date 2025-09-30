<?php

use App\Http\Controllers\Manager\AccessoriesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\DashboardController;
use App\Http\Controllers\Manager\RentalController;
use App\Http\Controllers\Manager\CustomerController;
use App\Http\Controllers\Manager\ItemController;
use App\Http\Controllers\Manager\MaintenanceController;
use App\Http\Controllers\Manager\ProblemController;
use App\Http\Controllers\Manager\ReportController;
use App\Http\Controllers\Manager\ReportServiceController;
use App\Http\Controllers\Manager\ReportProblemController;
use App\Http\Controllers\Manager\ReportMaintenaceController;
use App\Http\Controllers\Manager\ServiceController;
use App\Http\Controllers\Manager\PembayaranController;


Route::group(['middleware' => ['auth:web', 'role:manager'], 'prefix' => 'manager'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/', [DashboardController::class, 'index']);

    //rental
    Route::resource('/rental',RentalController::class)->names('manager.rental');
    Route::get('/hsty/rental',[RentalController::class, 'hsty'])->name('manager.rental.hsty');
    Route::get('/problems', [ProblemController::class, 'index'])->name('manager.rental.problems');
    Route::post('/finis/{id}', [RentalController::class, 'finis'])->name('manager.rental.finis');
    Route::post('/problem/{id}/rental', [RentalController::class, 'problem'])->name('manager.rental.problem');
    Route::put('/rental/date/{id}', [RentalController::class, 'tanggalBuat'])->name('manager.rental.tanggalbuat');
    Route::post('/rental/deleteimage', [RentalController::class, 'deleteImage'])->name('manager.rental.deleteImage');
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('manager.pembayaran.index');
    Route::put('/pembayaran/{id}', [PembayaranController::class, 'bayar'])->name('manager.pembayaran.bayar');
    Route::get('/pembayaran/filter', [PembayaranController::class, 'filter'])->name('manager.pembayaran.filter');
    Route::get('/pembayaran/filter', [PembayaranController::class, 'filter'])->name('manager.pembayaran.filter');
    Route::put('/pembayaran/edit/{id}', [PembayaranController::class, 'update'])->name('manager.update.totalinv');
    Route::get('pinjaman/divisi', [RentalController::class, 'divisi'])->name('manager.rental.divisi');
    //rental end

    //problem
    Route::post('/problems/{id}/finis', [ProblemController::class, 'destroy'])->name('manager.problem.finis');
    Route::post('/problems/{id}/returned', [ProblemController::class, 'returned'])->name('manager.problem.returned');
    Route::post('/problem/', [ProblemController::class, 'store'])->name('manager.problem.store');
    //problem end

    //customer
    Route::resource('/customer',CustomerController::class)->names('manager.customer');
    //customer end

    //item
    Route::resource('/item', ItemController::class)->names('manager.item');
    Route::post('/items/{id}/mainten', [ItemController::class, 'mainten'])->name('items.mainten');
    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('manager.mainten.index');
    Route::post('/maintenance/{id}/item', [MaintenanceController::class, 'destroy'])->name('manager.mainten.item');
    Route::post('/maintenance/', [MaintenanceController::class, 'store'])->name('manager.mainten.store');
    Route::get('/items/sale', [ItemController::class, 'sale'])->name('manager.sale');
    Route::post('/sale/', [ItemController::class, 'storesale'])->name('manager.item.sale');
    Route::delete('/sale/{id}/', [ItemController::class, 'deleteSale'])->name('manager.sale.destroy');
    Route::post('/item/deleteimage', [ItemController::class, 'deleteImage'])->name('manager.item.deleteImage');
    //item end

    //accessories
    Route::resource('/accessories', AccessoriesController::class)->names('manager.acces');
    Route::get('accessories-kosong/', [AccessoriesController::class, 'accesKosong'])->name('manager.acces.kosong');
    //accessories end

    //report
    Route::get('/report', [ReportController::class, 'index'])->name('manager.rental.report');
    Route::get('/report/filter', [ReportController::class, 'filter'])->name('manager.report.filter');
    Route::get('/mainten/report', [ReportMaintenaceController::class, 'index'])->name('manager.mainten.report');
    Route::get('/mainten/report/filter', [ReportMaintenaceController::class, 'filter'])->name('manager.mainten.filter');
    Route::get('/problem/report', [ReportProblemController::class, 'index'])->name('manager.problem.report');
    Route::get('/problem/report/filter', [ReportProblemController::class, 'filter'])->name('manager.problem.filter');
    Route::get('report/service', [ReportServiceController::class, 'index'])->name('manager.report.service.index');
    Route::get('/report/service/filter', [ReportServiceController::class, 'filter'])->name('manager.service.filter');
    Route::resource('/service', ServiceController::class)->names('manager.service');
    Route::get('/history/service', [ServiceController::class, 'history'])->name('manager.service.history');
    Route::put('/service/finis/{id}', [ServiceController::class, 'finis'])->name('manager.service.finis');
    Route::put('/pembayaran/service/{id}', [ServiceController::class, 'bayar'])->name('manager.service.bayar');
    Route::put('/invoice/edit/{id}', [ServiceController::class, 'invoice'])->name('manager.service.invoice');
    Route::put('/invoices/edit/{id}', [ServiceController::class, 'invoices'])->name('manager.service.invoices');
    Route::get('/report/filter/cicilan', [ReportController::class, 'filtercicilan'])->name('manager.report.filtercicilan');
    Route::delete('/debts/delete/{id}', [PembayaranController::class, 'destroy'])->name('manager.debts.hapus');
    Route::delete('/rent/delete/{id}', [PembayaranController::class, 'destroyRental'])->name('manager.rent.hapus');
    //end report
});
