<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GridTemplate>
 */
class GridTemplateFactory extends Factory
{
     /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'workload'=> collect(['80','60','100'])->random(),
        ];
    }

    public function forCourse($courseId)
    {
        return $this->state(function (array $attributes) use ($courseId) {
            return [
                'course_id' => $courseId,
            ];
        });
    }

    public function forDiscipline($disciplineId)
    {
        return $this->state(function (array $attributes) use ($disciplineId) {

            return [
                'discipline_id' => $disciplineId,
            ];
        });
    }

    public function forStage($stageId)
    {
        return $this->state(function (array $attributes) use ($stageId) {
            return [
                'stage_id' => $stageId,
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
