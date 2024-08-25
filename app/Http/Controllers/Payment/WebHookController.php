<?php

namespace App\Http\Controllers\Payment;

use App\Models\Financial;
use Illuminate\Http\Request;
use App\Services\MercadoPagoOrder;
use App\Http\Controllers\Controller;
use App\Services\MercadoPagoService;

class WebHookController extends Controller
{
    public function order(Request $request)
    {
        $response = $request->all();

        #\Illuminate\Support\Facades\Log::info(print_r($response, 1));

        $pagamento_id = $response['data_id'];

        $mp = (new MercadoPagoOrder(new MercadoPagoService()))->showPayment($pagamento_id);

        $financial = Financial::find($mp['items'][0]['id']);

        if ($financial && $mp['status'] == 'approved') { // status=rejected

            $financial->paid = 1;
            $financial->gateway_response = $mp;
            $financial->pay_day = date('Y-m-d');
            $financial->save();

        } else if ($financial) {

            $financial->gateway_response = $mp;
            $financial->save();
        }

        \Illuminate\Support\Facades\Log::info('MP: ' . "FinancialId: ". $mp['items'][0]['id'] ." / Status: {$mp['status']}\n");

        return $response;
    }
}
