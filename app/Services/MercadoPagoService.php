<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class MercadoPagoService
{
    protected $apiKey;
    protected $apiUrl;
    protected $accessToken;

    public function __construct()
    {
        $this->apiKey = config('services.mercadopago.public_key');
        $this->apiUrl = config('services.mercadopago.api_url');
        $this->accessToken = config('services.mercadopago.access_token');
    }

    public function request($method, $endpoint, $data = [])
    {

        // @TODO serve para nao repetir a mesma requisicao e gerar o mesmo boleto com mesmos dados.
        $uuid = Str::uuid()->toString();

        $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $this->accessToken,
                        'X-Idempotency-Key' => $uuid,
                    ])
                    ->$method($this->apiUrl . $endpoint, $data);

        if ($response->failed()) 
            throw new \Exception('Erro na requisição para MercadoPago: ' . $response->body());

        return $response->json();
    }
}
