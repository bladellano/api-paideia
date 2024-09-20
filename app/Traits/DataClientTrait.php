<?php

namespace App\Traits;

use App\Models\Client;

trait DataClientTrait
{
  protected function getClient()
  {
    $client = Client::first(); /** Sempre um único registro da tabela. */

    if (!$client) 
      return response()->json(['error' => 'Client not found'], 404);

    return $client;
  }
}
