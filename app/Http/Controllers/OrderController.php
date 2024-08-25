<?php

namespace App\Http\Controllers;

use App\Models\Financial;
use Illuminate\Http\Request;
use App\Services\MercadoPagoOrder;
use App\Services\MercadoPagoService;

class OrderController extends Controller
{

    public function pendingPayment(Request $request)
    {
        return view('payment.mp-page-pending', compact($request));
    }

    public function failurePayment(Request $request)
    {
        return view('payment.mp-page-failure', compact($request));
    }

    public function successPayment(Request $request)
    {
        if ($request->status != 'approved')
            return view('payment.mp-page-failure');

        $mp = (new MercadoPagoOrder(new MercadoPagoService()))->showPreference($request->preference_id);

        $financial = Financial::find($mp['items'][0]['id']);

        $financial->paid = 1;
        $financial->pay_day = date('Y-m-d');
        $financial->gateway_response = $mp;
        $financial->save();

        return view('payment.mp-page-success', compact('financial'));
    }
}
