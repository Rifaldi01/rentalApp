<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\DashboardController;
use App\Http\Controllers\Manager\RentalController;
use App\Http\Controllers\Manager\CustomerController;
use App\Http\Controllers\Manager\ProblemController;
use App\Http\Controllers\Manager\ReportController;
use App\Http\Controllers\Manager\ReportServiceController;
use App\Http\Controllers\Manager\ReportProblemController;
use App\Http\Controllers\Manager\ReportMaintenaceController;
use App\Http\Controllers\Manager\ServiceController;


Route::group(['middleware' => ['auth:web', 'role:manager'], 'prefix' => 'manager'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/', [DashboardController::class, 'index']);

    //rental
    Route::resource('/rental',RentalController::class)->names('manager.rental');
    Route::get('/hsty/rental',[RentalController::class, 'hsty'])->name('manager.rental.hsty');
    Route::get('/problems', [ProblemController::class, 'index'])->name('manager.rental.problems');
    Route::post('/finis/{id}', [RentalController::class, 'finis'])->name('manager.rental.finis');
    Route::post('/problem/{id}/rental', [RentalController::class, 'problem'])->name('manager.rental.problem');

    //rental end

    //customer
    Route::resource('/customer',CustomerController::class)->names('manager.customer');
    //customer end

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
    Route::post('/service/finis/{id}', [ServiceController::class, 'finis'])->name('manager.service.finis');
    //end report
});
