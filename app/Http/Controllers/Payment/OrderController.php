<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Services\PagarMeOrder;
use App\Http\Controllers\Controller;
use App\Models\Financial;
use App\Services\MercadoPagoOrder;

class OrderController extends Controller
{
    protected $pagarMeOrder;
    protected $mercadoPagoOrder;

    public function __construct(PagarMeOrder $pagarMeOrder, MercadoPagoOrder $mercadoPagoOrder)
    {
        $this->pagarMeOrder = $pagarMeOrder;
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

    public function storePreference(Financial $financial) //! MERCADO PAGO.
    {

        $response = json_decode($financial->gateway_response);

        /** Verifica se ja existe uma preferencia gravada na pendencia financeira. */
        if (!empty($financial->gateway_response) && $response instanceof \stdClass && isset($response->init_point))
            return $financial->gateway_response;

        $postData = [
            "items" => [
                [
                    "id" => $financial->id,
                    "title" => "{$financial->serviceType->name} ({$financial->paymentType->name}) {$financial->observations}",
                    "quantity" => 1,
                    "currency_id" => "BRL",
                    "unit_price" => $financial->value
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

    public function customers() //! MERCADO PAGO.
    {
        try {
            $data = $this->mercadoPagoOrder->showCustomers();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function payments() //! MERCADO PAGO.
    {
        try {
            $data = $this->mercadoPagoOrder->showPayments();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function index()
    {
        try {
            $order = $this->pagarMeOrder->getAll();
            return response()->json($order);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function create(Request $request)
    {

        try {

            $order = $this->pagarMeOrder->createOrder($request->all());

            Financial::find($order['code'])->update(['gateway_response' => json_encode($order)]);
            /** Atualiza o financial com response da api */

            return response()->json($order, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $order = $this->pagarMeOrder->getOrder($id);
            return response()->json($order);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
