<?php

namespace App\Http\Controllers\Payment;

use App\Models\Financial;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\MercadoPagoOrder;
use App\Http\Controllers\Controller;
use App\Services\MercadoPagoService;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PaymentStatusNotification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class WebHookController extends Controller
{
    public function order(Request $request): JsonResponse
    {
        $response = $request->all();
        $pagamento_id = $response['data_id'] ?? null;

        if (!$pagamento_id) 
            return response()->json(['message' => 'ID de pagamento não encontrado.'], Response::HTTP_BAD_REQUEST);

        try {
            $mp = (new MercadoPagoOrder(new MercadoPagoService()))->showPayment($pagamento_id);

            $financial_id = self::getFinancialId($mp);
            $financial = Financial::find($financial_id);

            if ($financial) 
                $this->updateFinancialStatus($financial, $mp);

            $message = $this->buildNotificationMessage($financial, $mp, $pagamento_id, $financial_id);
            $this->sendEmailNotification($message);
            Log::info($message);

            return response()->json(['message' => 'Processado com sucesso.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => "ID: $pagamento_id - " . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function orderTest(Request $request): void
    {
        $response = $request->all();
        $pagamento_id = $response['data_id'] ?? null;

        if (!$pagamento_id) {
            Log::error('ID de pagamento não encontrado para teste.');
            return;
        }

        $mp = (new MercadoPagoOrder(new MercadoPagoService()))->showPayment($pagamento_id);
        $message = json_encode($response);

        Notification::route('mail', 'dellanosites@gmail.com')->notify(new PaymentStatusNotification($message));
        Log::info("TEST: PAGTO_ID: {$pagamento_id} / STATUS: {$mp['status']} / DESCR.: {$mp['description']}");
    }

    private function sendEmailNotification(string $message): void
    {
        Notification::route('mail', ['dellanosites@gmail.com', 'bladellano@gmail.com', 'diretor@paideiaeducacional.com'])
            ->notify(new PaymentStatusNotification($message));
    }

    private static function parseStringToId(string $string): ?int
    {
        if (preg_match('/\#(\d+) -/', $string, $matches)) 
            return (int) $matches[1];

        return null;
    }

    private static function getFinancialId(array $mp): ?int
    {
        return $mp['payment_type_id'] === 'ticket'
            ? self::parseStringToId($mp['description'])
            : $mp['additional_info']['items'][0]['id'] ?? null;
    }

    private static function updateFinancialStatus(Financial $financial, array $mp): void
    {
        $financial->gateway_response = $mp;

        if ($mp['status'] === 'approved') {
            $financial->paid = 1;
            $financial->pay_day = Carbon::now();
        }

        $financial->updated_at = Carbon::now();
        $financial->save();
    }

    private static function buildNotificationMessage(?Financial $financial, array $mp, ?string $pagamento_id, ?int $financial_id): string
    {
        $color = $mp['status'] === 'approved' ? "#008000" : "#FFA500";
        $payDay = $financial && $mp['status'] === 'approved' ? Carbon::parse($financial->pay_day)->format('d/m/Y H:i:s') : '--';
        $paymentType = $financial ? mb_strtoupper($financial->paymentType->name) : '';
        $registrationId = $financial ? $financial->registration_id : '';
        $studentName = $financial ? mb_strtoupper($financial->registration->student->name) : '';
        $quota = $financial ? str_pad($financial->quota ?? '00', 2, '0', STR_PAD_LEFT) : '';
        $dueDate = $financial ? mb_strtoupper(Carbon::parse($financial->due_date)->locale('pt_BR')->translatedFormat('F/Y')) : '';
        $value = $financial ? number_format($financial->value, 2, ',', '.') : '0,00';
        $observations = $financial ? $financial->observations : '';

        return "<p><b>DETALHES INTEGRAÇÃO MP:</b></p>
                <b>ID_PAIDEIA:</b> {$financial_id}<br/>
                <b>ID_PAGTO_MP:</b> {$pagamento_id}<br/><hr/>
                <b>STATUS INTEGRAÇÃO MP:</b> <span style='color:{$color}'>{$mp['status']}</span><br/>
                <b>PAGO EM:</b> {$payDay}<br/>
                <b>FORMA DE PAGTO:</b> {$paymentType}<br/><hr/>
                <b>MATRÍCULA:</b> {$registrationId}<br/>
                <b>ALUNO:</b> {$studentName}<br/>
                <b>PARCELA:</b> {$quota}<br/>
                <b>VENCIMENTO:</b> {$dueDate} <br/>
                <b>VALOR:</b> R$ {$value}<br/>
                <b>OBS.:</b> {$observations}<br/><br/>";
    }
}
