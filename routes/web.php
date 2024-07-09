<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Employe\DashboardController;
use App\Http\Controllers\EditController;
use App\Http\Controllers\employe\ItemController;
use App\Http\Controllers\Employe\AccessoriesController;
use App\Http\Controllers\Employe\MaintenanceController;

Auth::routes([
    'register' => false
]);
Route::group(['middleware' => ['auth:web']], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::resource('/profile/edit', EditController::class)->names('profile.edit');
    Route::get('item', [ItemController::class, 'index'])->name('employe.index');
    Route::get('/accessories', [AccessoriesController::class, 'index'])->name('employe.acces');
    Route::get('/item/sale', [ItemController::class, 'sale'])->name('employe.sale');
    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('employe.mainten');
});


