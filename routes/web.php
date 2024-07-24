<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Payment\OrderController;
use App\Http\Controllers\OrderController as OrderWeb;

Route::get('/', function () {
    return view('welcome');
});

/** Gateway pagamento - Front */
Route::get('payment/create-order/{financial}', [OrderWeb::class, 'create'])->name('payment.orders.create');
// Route::post('payment/orders', [OrderController::class, 'create'])->name('payment.orders.store');


