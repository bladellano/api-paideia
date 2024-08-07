<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Services\PagarMeOrder;
use App\Http\Controllers\Controller;
use App\Models\Financial;

class OrderController extends Controller
{
    protected $pagarMeOrder;

    public function __construct(PagarMeOrder $pagarMeOrder)
    {
        $this->pagarMeOrder = $pagarMeOrder;
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

            Financial::find($order['code'])->update(['gateway_response' => json_encode($order)]); /** Atualiza o financial com response da api */

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
