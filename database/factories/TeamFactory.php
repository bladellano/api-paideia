<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'=> 'Turma '. mb_strtoupper($this->faker->lexify('????')),
            'start_date'=> $this->faker->dateTimeBetween('-4 month', 'now')->format('Y-m-d H:i:s'),
            'end_date'=> $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s'),
        ];
    }

    public function forPolo($poloId)
    {
        return $this->state(function (array $attributes) use ($poloId) {
            return [
                'polo_id' => $poloId,
            ];
        });
    }

    public function forGrid($gridId)
    {
        return $this->state(function (array $attributes) use ($gridId) {
            return [
                'grid_id' => $gridId,
            ];
        });
    }
}
