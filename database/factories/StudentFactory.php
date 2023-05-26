<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition()
    {

        return [
            'name'=> $this->faker->name,
            'email' => $this->faker->safeEmail(),
            'phone'=> $this->faker->numerify('(91)9####-####'),
            'cpf'=> $this->faker->numerify('###########'),
            'rg'=> $this->faker->numerify('#######'),
            'expedient_body'=> 'SEGUP/PA',
            'rg'=> $this->faker->numerify('#######'),
            'nationality'=> $this->faker->country,
            'naturalness'=> $this->faker->city,
            'name_mother'=> $this->faker->name('female'),
            'birth_date'=> $this->faker->dateTimeBetween('-24 year', 'now')->format('Y-m-d'),
            'gender' => collect(['M', 'F'])->random()
        ];
    }
}
