<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Marketing\HistoryController;


Route::group(['middleware' => ['auth:web', 'role:marketing'], 'prefix' => 'marketing'], function () {
    //rental
    Route::get('/histori/rental',[HistoryController::class, 'rental'])->name('marketing.rental');
    Route::get('/histori/service', [HistoryController::class, 'service'])->name('marketing.service');
});
