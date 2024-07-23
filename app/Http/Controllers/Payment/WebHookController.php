<?php

namespace App\Http\Controllers\Payment;

use App\Models\Financial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WebHookController extends Controller
{
    public function order(Request $request)
    {

        $response = $request->all();

        if (!$response)
            return;

        if ($response['type'] == 'order.paid') {

            $code = $response['data']['code'];

            /** 2 - Algo de errado aconteceu, aferir! */
            $status = $response['data']['status'] == 'paid' ? 1 : 2;

            $vtData = [
                'paid' => $status,
                'pay_day' => $status == 1 ? date('Y-m-d') : NULL,
                'gateway_response' => json_encode($response),
            ];

            Financial::find($code)->update($vtData);
        }

        // Log para depuração (opcional, remova em produção)
        \Illuminate\Support\Facades\Log::info('Pagar.me Webhook Received: ' . "Id: {$response['id']} / Code: {$response['data']['code']} / Status: {$response['data']['status']}",);
    }
}
