<?php

namespace App\Http\Controllers\Payment;

use Carbon\Carbon;
use App\Models\Financial;
use Illuminate\Http\Request;
use App\Services\MercadoPagoOrder;
use App\Http\Controllers\Controller;

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

    function getFirstAndLastName($fullName)
    {
        $nameParts = explode(' ', trim($fullName));
        $firstName = $nameParts[0];

        $lastName = $nameParts[count($nameParts) - 1];

        return [
            'first' => mb_strtoupper($firstName),
            'last' => mb_strtoupper($lastName)
        ];
    }

    public function storeTicket(Financial $financial, Request $request)
    {

        try {

            if (empty($financial->registration->student->name) || empty($financial->registration->student->email) || empty($financial->registration->student->cpf))
                throw new \Exception('Por favor, verifique se os dados dos alunos, como nome, e-mail e CPF, estão preenchidos.');

            $names = $this->getFirstAndLastName($financial->registration->student->name);
            $quota = str_pad($financial->quota ?? '00', 2, '0', STR_PAD_LEFT);
            $due_date = mb_strtoupper(\Carbon\Carbon::parse($financial->due_date)->locale('pt_BR')->translatedFormat('F/Y'));
            $rate = config('services.mercadopago.rate');
            $value = $financial->value + $rate;

            $postData = [
                "transaction_amount" => $value,
                "description" => "#{$financial->id} - PARC. DE N.º {$quota} - {$due_date} | " . mb_strtoupper($financial->registration->student->name) . " | " . mb_strtoupper($financial->registration->team->name),
                "payment_method_id" => "bolbradesco",
                "payer" => [
                    "email" => $financial->registration->student->email,
                    "first_name" => $names['first'],
                    "last_name" => $names['last'],
                    "identification" => [
                        "type" => "CPF",
                        "number" => $financial->registration->student->cpf
                    ],
                    "address" => [
                        "zip_code" => "67130450",
                        "street_name" => $financial->registration->student->naturalness,
                        "street_number" => "111",
                        "neighborhood" => "Cidade Nova",
                        "city" => "Ananindeua",
                        "federal_unit" => "PA"
                    ]
                ]
            ];

            if(isset($request->date_of_expiration) && !empty($request->date_of_expiration)) {
                $postData['date_of_expiration'] = Carbon::parse($request->date_of_expiration, 'UTC')->format('Y-m-d\TH:i:s.vP');
                $postData['external_reference'] = (string)$financial->id; //@TODO Significa que o boleto tem um vencimento customizado.
            }

            $data = $this->mercadoPagoOrder->createTicket($postData);

            $financial->gateway_response = $data;
            $financial->save();

            return response()->json($data);
        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function storePreference(Financial $financial)
    {

        $due_date = mb_strtoupper(\Carbon\Carbon::parse($financial->due_date)->locale('pt_BR')->translatedFormat('F/Y'));
        $quota = str_pad($financial->quota ?? '00', 2, '0', STR_PAD_LEFT);
        $rate = config('services.mercadopago.rate');
        $value = $financial->payment_type == $this->idPaymentTicket ? ($financial->value + $rate) : $financial->value;

        $postData = [
            "items" => [
                [
                    "id" => $financial->id,
                    "title" => "#{$financial->id} - PARC. DE N.º {$quota} - {$due_date} | " . mb_strtoupper($financial->registration->student->name) . " | " . mb_strtoupper($financial->registration->team->name),
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
