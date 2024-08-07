<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Payment\OrderController;
use App\Http\Controllers\OrderController as OrderWeb;

Route::get('/', function () {
    return view('welcome');
});

/** Gateway pagamento - Front */
Route::get('payment/create-order/{financial}', [OrderWeb::class, 'create'])->name('payment.orders.create');
Route::get('payment/create-order-ticket/{financial}', [OrderWeb::class, 'createTicket'])->name('payment.orders.create-ticket');


