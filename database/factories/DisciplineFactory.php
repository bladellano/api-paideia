<?php

namespace Database\Factories;

use App\Models\Teaching;
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
            'name'=> collect(['MATEMÁTICA', 'CIÊNCIAS','PORTUGUÊS','LITERATURA','INGLÊS','QUÍMICA','GEOGRAFIA','DIGITAÇÃO','FÍSICA','SOCIOLOGIA','FILOSOFIA','BIOLOGIA'])->random(),
            'amount_of_reviews' => $this->faker->numberBetween(1, 4),
            'workload' => collect(['80', '60','100','150','70'])->random(),
            'teaching_id' => collect([3,4,2])->random(),
            // 'teaching_id' => Teaching::all()->random()->id,
        ];
    }
}
