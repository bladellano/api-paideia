<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Financial;
use App\Services\MercadoPagoOrder;

class OrderController extends Controller
{
    protected $mercadoPagoOrder;
    protected $idPaymentTicket = 4;

    public function __construct(MercadoPagoOrder $mercadoPagoOrder)
    {
        $this->mercadoPagoOrder = $mercadoPagoOrder;
    }

    public function getPayment($id)
    {
        try {
            $data = $this->mercadoPagoOrder->showPayment($id);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function getPreference($uui)
    {
        try {
            $data = $this->mercadoPagoOrder->showPreference($uui);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function storePreference(Financial $financial)
    {

        $due_date = mb_strtoupper(\Carbon\Carbon::parse($financial->due_date)->locale('pt_BR')->translatedFormat('F/Y'));
        $quota = str_pad($financial->quota ?? '00', 2, '0', STR_PAD_LEFT);
        $value = $financial->payment_type == $this->idPaymentTicket ? ($financial->value + 4.49) : $financial->value;

        $postData = [
            "items" => [
                [
                    "id" => $financial->id,
                    "title" => "#{$financial->id} [{$quota}] - {$due_date} | " . mb_strtoupper($financial->registration->student->name) . " | " . mb_strtoupper($financial->registration->team->name),
                    "quantity" => 1,
                    "currency_id" => "BRL",
                    "unit_price" => $value
                ]
            ],
            "back_urls" => [
                "success" => config('app.url') . "/pagamento-sucesso",
                "failure" => config('app.url') . "/pagamento-falhou",
                "pending" => config('app.url') . "/pagamento-pendente"
            ],
            "auto_return" => "all"
        ];

        try {
            $data = $this->mercadoPagoOrder->createPreference($postData);

            $financial->gateway_response = $data;
            $financial->save();

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function customers()
    {
        try {
            $data = $this->mercadoPagoOrder->showCustomers();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function payments()
    {
        try {
            $data = $this->mercadoPagoOrder->showPayments();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
