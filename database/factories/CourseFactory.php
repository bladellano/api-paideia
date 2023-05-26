<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'=> "Curso ". $this->faker->name,
        ];
    }


    public function forTeaching($teachingId)
    {
        return $this->state(function (array $attributes) use ($teachingId) {
            return [
                'teaching_id' => $teachingId,
            ];
        });
    }
}
