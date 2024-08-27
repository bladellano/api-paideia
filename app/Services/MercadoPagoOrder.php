<?php

namespace App\Services;

class MercadoPagoOrder
{
    protected $mercadoPagoService;

    public function __construct(MercadoPagoService $mercadoPagoService)
    {
        $this->mercadoPagoService = $mercadoPagoService;
    }

    public function createTicket($orderData)
    {
        return $this->mercadoPagoService->request('post', '/v1/payments', $orderData);
    }

    public function createPreference($orderData)
    {
        return $this->mercadoPagoService->request('post', '/checkout/preferences', $orderData);
    }

    public function showPayment($id)
    {
        return $this->mercadoPagoService->request('get', "/v1/payments/{$id}");
    }

    public function showPreference($uudi)
    {
        return $this->mercadoPagoService->request('get', "/checkout/preferences/{$uudi}");
    }

    public function showCustomers()
    {
        return $this->mercadoPagoService->request('get', '/v1/customers/search');
    }

    public function showPayments()
    {
        return $this->mercadoPagoService->request('get', '/v1/payments/search');
    }
  
}
