<?php

namespace App\Services;

class PagarMeOrder
{
    protected $pagarMeService;

    public function __construct(PagarMeService $pagarMeService)
    {
        $this->pagarMeService = $pagarMeService;
    }

    public function createOrder($orderData)
    {
        return $this->pagarMeService->request('post', '/orders', $orderData);
    }

    public function getOrder($orderId)
    {
        return $this->pagarMeService->request('get', '/orders/' . $orderId);
    }

    public function getAll()
    {
        return $this->pagarMeService->request('get', '/orders');
    }

}
