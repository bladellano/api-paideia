<?php

namespace App\Http\Controllers;

use App\Models\Financial;

class OrderController extends Controller
{

    public function create(Financial $financial)
    {
        $states = config('states');
        $financial->serviceType;
        $financial->registration;

        if($financial->paid == 1)
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

        if($response && isset($response->charges[0]->last_transaction)) {
            //! @TODO - Futuramente utilizar barcode e qrycode.
            $charges = $response->charges[0]->last_transaction;
            $boletoPDF = $charges->pdf;
        }

        if($financial->paid == 1) {
            return view('payment.paid-order', compact('financial'));
        }

        //! @TODO - Verificar se um boleto já foi criado e, em caso positivo, exibir o código de barras, o link do boleto, entre outras informações relevantes.

        return view('payment.create-order-ticket', compact('financial', 'states', 'boletoPDF'));
    }
}
