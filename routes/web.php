<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderWebController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ClientController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('pagamento-sucesso', [OrderWebController::class, 'successPayment'])->name('payment.success');
Route::get('pagamento-falhou', [OrderWebController::class, 'failurePayment'])->name('payment.failure');
Route::get('pagamento-pendente', [OrderWebController::class, 'pendingPayment'])->name('payment.pending');

Route::get('modules/generate-menu', [ModuleController::class, 'generateMenu']);
Route::resource('modules', ModuleController::class);

Route::get('clients', [ClientController::class, 'index'])->name('clients.index');
Route::get('clients/create', [ClientController::class, 'create'])->name('clients.create');
Route::post('clients', [ClientController::class, 'store'])->name('clients.store');

