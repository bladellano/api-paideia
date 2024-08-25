<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderWebController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('pagamento-sucesso', [OrderWebController::class, 'successPayment'])->name('payment.success');
Route::get('pagamento-falhou', [OrderWebController::class, 'failurePayment'])->name('payment.failure');
Route::get('pagamento-pendente', [OrderWebController::class, 'pendingPayment'])->name('payment.pending');


