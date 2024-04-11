<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class FinancialFactory extends Factory
{
    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition()
    {

        $dueDate = $this->faker->dateTimeBetween('now', '+6 months');

        return [
            'value' => $this->faker->randomFloat(2, 50, 500),
            'due_date' => $dueDate,
            'paid' => $this->faker->boolean(70),
            'observations' => $this->faker->sentence,
            'gateway_response' => $this->faker->text,
            'payment_type' => \App\Models\PaymentType::inRandomOrder()->first()->id,
            'registration_id' => \App\Models\Registration::inRandomOrder()->first()->id,
            'service_type_id' => \App\Models\ServiceType::inRandomOrder()->first()->id,
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
