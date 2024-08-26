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

        $color = "#FFA500";

        if ($financial && $mp['status'] == 'approved') { // status=rejected

            $color = "#008000";
            $financial->paid = 1;
            $financial->gateway_response = $mp;
            $financial->pay_day = date('Y-m-d H:m:s');
            $financial->save();
        } else if ($financial) {

            $financial->gateway_response = $mp;
            $financial->save();
        }

        $message = "<p><b>DETALHES INTEGRAÇÃO MP:</b></p>";
        $message .= "<b>ID_PAIDEIA:</b> {$financial_id}<br/>";
        $message .= "<b>ID_PAGTO_MP:</b> {$pagamento_id}<br/><hr/>";

        $message .= "<b>STATUS INTEGRAÇÃO MP:</b> <span style='color:{$color}'>{$mp['status']}</span><br/>";
        $message .= "<b>PAGO EM:</b> " . \Carbon\Carbon::parse($financial->pay_day)->format('d/m/Y H:m:s') . "<br/>";
        $message .= "<b>FORMA DE PAGTO:</b> " . mb_strtoupper($financial->paymentType->name) . "<br/><hr/>";

        $message .= "<b>MATRÍCULA:</b> {$financial->registration_id}<br/>";
        $message .= "<b>ALUNO:</b> " . mb_strtoupper($financial->registration->student->name) . "<br/>";
        $message .= "<b>PARCELA:</b> " . str_pad($financial->quota ?? '00', 2, '0', STR_PAD_LEFT) . "<br/>";
        $message .= "<b>VENCIMENTO:</b> " . mb_strtoupper(\Carbon\Carbon::parse($financial->due_date)->locale('pt_BR')->translatedFormat('F/Y')) . " <br/>";
        $message .= "<b>VALOR:</b> R$ " . number_format($financial->value, 2, ',', '.') . "<br/>";
        $message .= "<b>OBS.:</b> {$financial->observations}<br/><br/>";

        $this->sendEmailNotification($message);

        \Illuminate\Support\Facades\Log::info($message);

        return $response;
    }

    /**
     * Função para enviar e-mail de notificação
     */
    private function sendEmailNotification($message)
    {
        Notification::route('mail', ['dellanosites@gmail.com', 'bladellano@gmail.com', 'diretor@paideiaeducacional.com'])->notify(new PaymentStatusNotification($message));
    }
}
