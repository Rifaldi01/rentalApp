<?php

use App\Http\Controllers\RentalDivisiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Employe\DashboardController;
use App\Http\Controllers\EditController;
use App\Http\Controllers\Employe\ItemController;
use App\Http\Controllers\Employe\AccessoriesController;
use App\Http\Controllers\Employe\MaintenanceController;
use App\Http\Controllers\Employe\RentalController;
use App\Http\Controllers\Employe\AccessoriesMaintenace;
use App\Http\Controllers\AccessoriesSaleController;

Route::get('/', function () {
    return redirect()->route('login');
});
Auth::routes([
    'register' => false
]);
Route::group(['middleware' => ['auth:web', 'role:employe'], 'prefix' => 'employe'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    Route::resource('/profile/edit', EditController::class)->names('profile.edit');

    Route::resource('item', ItemController::class)->names('employe.item');
    Route::get('/items/sale', [ItemController::class, 'sale'])->name('employe.sale');
    Route::post('/maintenance/{id}/item', [MaintenanceController::class, 'destroy'])->name('employe.mainten.item');
    Route::post('/item/deleteimage', [ItemController::class, 'deleteImage'])->name('employe.item.deleteImage');
    Route::post('/maintenance/', [MaintenanceController::class, 'store'])->name('employe.mainten.store');
    Route::get('riwayat/item/', [ItemController::class, 'itemin'])->name('employe.item.itemin');

    Route::get('/accessories', [AccessoriesController::class, 'index'])->name('employe.acces');
    Route::post('/sale/', [ItemController::class, 'storesale'])->name('employe.item.sale');
    Route::post('/accessories/store', [AccessoriesController::class, 'store'])->name('employe.acces.store');
    Route::put('/accessories/{id}', [AccessoriesController::class, 'update'])->name('employe.acces.update');
    Route::put('/acces-tambah/{id}', [AccessoriesController::class, 'tambah'])->name('employe.acces.tambah');
    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('employe.mainten');
    Route::get('riwayat/accessories/', [AccessoriesController::class,'accesin'])->name('employe.accessories.accesin');

});

Route::group(['middleware' => ['auth:web', 'role:employe|manager'], 'prefix' => 'employe'], function () {
    Route::get('maintenace-acces/', [AccessoriesMaintenace::class, 'index'])->name('employe.mainten.access');
    Route::post('maintenace-acces/', [AccessoriesMaintenace::class, 'accesStore'])->name('employ.maintenace.accesStore');
    Route::post('maintenace-acces/finis/{id}', [AccessoriesMaintenace::class, 'finis'])->name('employe.maintenace.finis');

    Route::resource('rental-divisi', RentalDivisiController::class)->names('employe.rentaldivisi');
    Route::post('rental-divisi/finis/{id}', [RentalDivisiController::class, 'finis'])->name('employe.rentaldivisi.finis');

    Route::resource('accessories-sale/', AccessoriesSaleController::class)->names('employe.accesSale');

    Route::post('rental-kembali/{id}', [RentalController::class, 'kembali'])->name('employe.rental.kembali');
    Route::post('rental-finis/{id}', [RentalController::class, 'finis'])->name('employe.rental.finis');
    Route::get('/rental', [RentalController::class, 'index'])->name('employe.rental');
    Route::post('/rental/{id}', [RentalController::class, 'approveRental'])->name('employe.rental.approve');

});



