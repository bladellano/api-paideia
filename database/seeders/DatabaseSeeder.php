<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @return void
     */
    public function run()
    {
        // Pólos
        \App\Models\Polo::factory(10)->create();

        // Ensinos com Cursos
        $teachings = [
            'Livre',
            'Profissionalizante',
            'Fundamental',
            'Médio',
            'Técnico',
            'Pós-Técnico',
            'Ensino Fundamental',
            'Ensino Superior',
            'Ensino Médio',
            'Graduação',
        ];

        foreach($teachings as $teaching){
            \App\Models\Teaching::factory()->create([
                'name' => $teaching,
                'description' => 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration'
            ]);
        }

        \App\Models\Course::factory()->create([
            'name' => 'EJA Fundamental 3º e 4º Etapa',
            'teaching_id' => 3,
        ]);

        \App\Models\Course::factory()->create([
            'name' => 'EJA Médio 1º e 2º Etapa',
            'teaching_id' => 4,
        ]);

        /*
        for ($i= 0; $i < 5; $i++) {
            $teachingId = \App\Models\Teaching::inRandomOrder()->first()->id;
            \App\Models\Course::factory()
            ->forTeaching($teachingId)
            ->create();
        } */
        /* \App\Models\Teaching::factory(10)
        ->hasCourses(2)
        ->create(); */

        // Etapas
        for ($i= 1; $i <= 2; $i++) {
            \App\Models\Stage::factory()->create([
                // 'name' => 'Etapa ' . $i,
                'stage' => $i,
                'description' => 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration',
            ]);
        }

        // Disciplinas
        \App\Models\Discipline::factory(10)->create();

        // Grades
        \App\Models\Grid::factory(5)->create();

        // Grades Templates
        $courseId = \App\Models\Course::inRandomOrder()->first()->id;
        $gridId = \App\Models\Grid::inRandomOrder()->first()->id;

        for ($i= 0; $i < 5; $i++) {
            $stageId = \App\Models\Stage::inRandomOrder()->first()->id;
            \App\Models\GridTemplate::factory()
            ->forCourse($courseId)
            ->forDiscipline(($i+1))
            ->forStage($stageId)
            // ->forGrid($gridId)
            ->forGrid(5)
            ->create();
        }

        // Turmas
        $poloId = \App\Models\Polo::inRandomOrder()->first()->id;
        $gridId = \App\Models\Grid::inRandomOrder()->first()->id;

        for ($i= 0; $i < 2; $i++) {
            \App\Models\Team::factory()
            ->forPolo($poloId)
            // ->forGrid($gridId)
            ->forGrid(5)
            ->create();
        }

        // Alunos
        \App\Models\Student::factory(10)
        ->create();

        // Alunos x Turmas
        $teamId = \App\Models\Team::inRandomOrder()->first()->id;
        $studentId = \App\Models\Student::inRandomOrder()->first()->id;

        // Usuário
        \App\Models\StudentTeam::factory()->create([
            'student_id' => $studentId,
            'team_id' => $teamId,
        ]);

        // Usuário
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
