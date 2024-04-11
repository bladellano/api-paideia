<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentType;

class PaymentTypesSeeder extends Seeder
{
    public function run()
    {
        $paymentTypes = [
            ['name' => 'Pix', 'description' => 'Pagamento via Pix', 'user_id' => \App\Models\User::inRandomOrder()->first()->id],
            ['name' => 'Cartão de Crédito', 'description' => 'Pagamento via cartão de crédito', 'user_id' => \App\Models\User::inRandomOrder()->first()->id],
            ['name' => 'À Vista', 'description' => 'Pagamento à vista', 'user_id' => \App\Models\User::inRandomOrder()->first()->id],
            ['name' => 'Boleto Bancário', 'description' => 'Pagamento via boleto bancário', 'user_id' => \App\Models\User::inRandomOrder()->first()->id],
        ];

        foreach ($paymentTypes as $paymentType)
            PaymentType::create($paymentType);

    }
}
