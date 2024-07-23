<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PagarMeService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.pagarme.api_key');
        $this->apiUrl = config('services.pagarme.api_url');
    }

    public function request($method, $endpoint, $data = [])
    {
        $response = Http::withBasicAuth($this->apiKey, '')
                        ->$method($this->apiUrl . $endpoint, $data);

        if ($response->failed()) 
            throw new \Exception('Erro na requisição para Pagar.me: ' . $response->body());

        return $response->json();
    }
}
