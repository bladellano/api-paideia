<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discipline>
 */
class DisciplineFactory extends Factory
{
    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition()
    {

        return [
            'name'=> "Disciplina ". $this->faker->name,
            'amount_of_reviews'=> $this->faker->numberBetween(1, 4),
            'workload'=> collect(['80', '60','100'])->random(),
        ];
    }
}
