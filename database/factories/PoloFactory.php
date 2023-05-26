<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Polo>
 */
class PoloFactory extends Factory
{
    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'=> 'Pólo ' . $this->faker->name,
            'city'=> $this->faker->city,
            'uf'=> collect(['PA','CE','MG','RJ'])->random(),
            'responsible'=> $this->faker->name,
            'address'=>$this->faker->streetName,
            'email'=> $this->faker->safeEmail,
            'phone'=> $this->faker->numerify('(91)9####-####'),
        ];
    }
}
