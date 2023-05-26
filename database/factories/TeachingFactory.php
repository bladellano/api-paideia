<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teaching>
 */
class TeachingFactory extends Factory
{
    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'=> "Ensino ". $this->faker->name,
            'description'=> $this->faker->sentence(4)
        ];
    }
}
