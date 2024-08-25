<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Payment\OrderController;
use App\Http\Controllers\OrderController as OrderWeb;

Route::get('/', function () {
    return view('welcome');
});

Route::get('pagamento-sucesso', [OrderWeb::class, 'successPayment'])->name('payment.success');
Route::get('pagamento-falhou', [OrderWeb::class, 'failurePayment'])->name('payment.failure');
Route::get('pagamento-pendente', [OrderWeb::class, 'pendingPayment'])->name('payment.pending');

/** Gateway pagamento - Front */
Route::get('payment/create-order/{financial}', [OrderWeb::class, 'create'])->name('payment.orders.create');
Route::get('payment/create-order-ticket/{financial}', [OrderWeb::class, 'createTicket'])->name('payment.orders.create-ticket');


