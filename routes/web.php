<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Employe\DashboardController;
use App\Http\Controllers\EditController;
use App\Http\Controllers\Employe\ItemController;
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
    Route::get('/item/create', [ItemController::class, 'create'])->name('employe.item.create');
    Route::post('/item', [ItemController::class, 'store'])->name('employe.item.store');
    Route::get('/item/{id}', [ItemController::class, 'show'])->name('employe.item.show');
    Route::get('/item/{id}/edit', [ItemController::class, 'edit'])->name('employe.item.edit');
    Route::put('/item/{id}', [ItemController::class, 'update'])->name('employe.item.update');
    Route::delete('/item/{id}', [ItemController::class, 'destroy'])->name('employe.item.destroy');
    Route::get('/items/sale', [ItemController::class, 'sale'])->name('employe.sale');
    Route::post('/maintenance/{id}/item', [MaintenanceController::class, 'destroy'])->name('employe.mainten.item');
    Route::post('/maintenance/', [MaintenanceController::class, 'store'])->name('employe.mainten.store');

    Route::get('/accessories', [AccessoriesController::class, 'index'])->name('employe.acces');
    Route::post('/sale/', [ItemController::class, 'storesale'])->name('employe.item.sale');
    Route::post('/accessories/store', [AccessoriesController::class, 'store'])->name('employe.acces.store');
    Route::put('/accessories/{id}', [AccessoriesController::class, 'update'])->name('employe.acces.update');
    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('employe.mainten');
});


