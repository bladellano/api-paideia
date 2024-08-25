<?php

namespace App\Http\Controllers\Payment;

use App\Models\Financial;
use Illuminate\Http\Request;
use App\Services\MercadoPagoOrder;
use App\Services\MercadoPagoService;
use App\Http\Controllers\Controller;
use App\Notifications\PaymentStatusNotification;
use Illuminate\Support\Facades\Notification;

class WebHookController extends Controller
{
    public function order(Request $request)
    {
        $response = $request->all();

        $pagamento_id = $response['data_id'];

        $mp = (new MercadoPagoOrder(new MercadoPagoService()))->showPayment($pagamento_id);

        $financial_id = $mp['additional_info']['items'][0]['id'];

        $financial = Financial::find($financial_id);

        if ($financial && $mp['status'] == 'approved') { // status=rejected

            $financial->paid = 1;
            $financial->gateway_response = $mp;
            $financial->pay_day = date('Y-m-d');
            $financial->save();

        } else if ($financial) {

            $financial->gateway_response = $mp;
            $financial->save();
        }

        $message = 'MP: ' . "FinancialId: " . $financial_id . " / Status: {$mp['status']}\n";
        $this->sendEmailNotification($message);

        \Illuminate\Support\Facades\Log::info($message);

        return $response;
    }

    /**
     * Função para enviar e-mail de notificação
     */
    private function sendEmailNotification($message)
    {
        Notification::route('mail', 'dellanosites@gmail.com')->notify(new PaymentStatusNotification($message));
    }
}
