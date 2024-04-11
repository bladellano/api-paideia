<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceType;

class ServiceTypesSeeder extends Seeder
{
    public function run()
    {
        $serviceTypes = [
            ['name' => 'Mensalidade', 'description' => 'Pagamento da mensalidade escolar', 'value' => 150, 'user_id' => \App\Models\User::inRandomOrder()->first()->id],
            ['name' => 'Matrícula', 'description' => 'Pagamento da matrícula escolar', 'value' => 80, 'user_id' => \App\Models\User::inRandomOrder()->first()->id],
            ['name' => 'Material Didático', 'description' => 'Compra de material didático', 'value' => 25, 'user_id' => \App\Models\User::inRandomOrder()->first()->id],
            ['name' => 'Atividades Extras', 'description' => 'Pagamento de atividades extracurriculares', 'value' => 50, 'user_id' => \App\Models\User::inRandomOrder()->first()->id],
        ];

        foreach ($serviceTypes as $serviceType)
            ServiceType::create($serviceType);
    }
}
