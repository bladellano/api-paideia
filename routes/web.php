<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController as OrderWeb;

Route::get('/', function () {
    return view('welcome');
});

Route::get('pagamento-sucesso', [OrderWeb::class, 'successPayment'])->name('payment.success');
Route::get('pagamento-falhou', [OrderWeb::class, 'failurePayment'])->name('payment.failure');
Route::get('pagamento-pendente', [OrderWeb::class, 'pendingPayment'])->name('payment.pending');


