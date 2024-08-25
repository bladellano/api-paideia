<?php

namespace App\Http\Controllers;

use App\Models\Financial;
use App\Services\MercadoPagoOrder;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function pendingPayment(Request $request)
    {
        return view('payment.mp-page-pending');
    }

    public function failurePayment(Request $request)
    {
        return view('payment.mp-page-failure');
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

    public function create(Financial $financial)
    {
        $states = config('states');
        $financial->serviceType;
        $financial->registration;

        if ($financial->paid == 1)
            return view('payment.paid-order', compact('financial'));

        return view('payment.create-order', compact('financial', 'states'));
    }

    public function createTicket(Financial $financial)
    {
        $states = config('states');
        $financial->serviceType;
        $financial->registration;

        $boletoPDF = "";

        $response = json_decode($financial->gateway_response);

        if ($response && isset($response->charges[0]->last_transaction)) {
            //! @TODO - No futuro, utilizar código de barras e QR code.
            $charges = $response->charges[0]->last_transaction;
            $boletoPDF = $charges->pdf;
        }

        if ($financial->paid == 1)
            return view('payment.paid-order', compact('financial'));

        //! @TODO - Verificar se um boleto já foi criado e, em caso positivo, exibir o código de barras, o link do boleto, entre outras informações relevantes.
        //! @TODO - Quando apagar um boleto pelo sistema Paideia, tem que remover do Pargarme também.
        //! @TODO - Notificar o aluno com o boleto: anexar ou exibir o link no corpo do e-mail.

        return view('payment.create-order-ticket', compact('financial', 'states', 'boletoPDF'));
    }
}
