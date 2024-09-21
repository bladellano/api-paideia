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

/** Menu para gerenciar dados do cliente/escola */

Route::prefix('admin')->group(function () {

    Route::get('/', function(){
        return view('modules.create');
    });

    Route::get('modules/generate-menu', [ModuleController::class, 'generateMenu']); //? Somente teste no insomnia.

    Route::resource('modules', ModuleController::class);
    Route::resource('clients', ClientController::class);
});
