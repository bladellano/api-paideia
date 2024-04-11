<?php

namespace Tests\App\Http\Controllers;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

# php artisan test --filter=RegistrationControllerTest
class RegistrationControllerTest extends TestCase
{

    # php artisan test --filter=RegistrationControllerTest::test_the_application_returns_a_successful_response
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    # php artisan test --filter=RegistrationControllerTest::test_store
    public function test_store()
    {
        //* Crie um usuário fictício para autenticar
        $user = User::factory()->create();

        //* Gere o token JWT para o usuário
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson(route('registrations.store'), [
            'student_id' => \App\Models\Student::inRandomOrder()->first()->id,
            'team_id' => \App\Models\Team::inRandomOrder()->first()->id,
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data',
            'message'
        ]);
    }
}
