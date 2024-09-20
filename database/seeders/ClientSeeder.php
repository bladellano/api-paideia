<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
  public function run()
  {

    Client::create([
      'school_name' => 'Paideia Educacional',
      'email' => 'contato@paideiaeducacional.com',
      'cnpj' => '32.599.936/0002-58',
      'address' => 'TV WE 17, Cidade Nova 2, N 111 - Coqueiro, Ananindeua - PA, 67130-450, Brasil',
      'phones' => ['(91)37229891', '(91)981769979','(91)982084651'],
      'owner' => 'William Borralho',
      'slogan' => 'Certifique seu futuro com confiança: sua conquista, nosso reconhecimento!',
      'main_service' => 'Verifique a autenticidade de seu Documento',
      'website_name' => 'https://validar-certificado.paideiaeducacional.com/',
      'colored_logo' => 'https://via.placeholder.com/200x150',
      'black_white_logo' => 'https://via.placeholder.com/200x150'
    ]);
  }
}
