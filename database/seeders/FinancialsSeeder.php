<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FinancialsSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Financial::factory(5)->create();
    }
}
