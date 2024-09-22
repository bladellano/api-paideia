<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ModuleController;
use App\Http\Middleware\CheckAdminPassword;
use App\Http\Controllers\OrderWebController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('pagamento-sucesso', [OrderWebController::class, 'successPayment'])->name('payment.success');
Route::get('pagamento-falhou', [OrderWebController::class, 'failurePayment'])->name('payment.failure');
Route::get('pagamento-pendente', [OrderWebController::class, 'pendingPayment'])->name('payment.pending');

/** Menu para gerenciar dados do cliente/escola */
Route::prefix('admin')->middleware(CheckAdminPassword::class)->group(function () {

    Route::get('/', fn() => (view('modules.create')));
    Route::get('/logout', function (Request $request) {
        $request->session()->forget('admin_logged_in');
        return redirect('/admin');
    })->name('admin.logout');

    Route::resource('modules', ModuleController::class);
    Route::resource('clients', ClientController::class);
});
