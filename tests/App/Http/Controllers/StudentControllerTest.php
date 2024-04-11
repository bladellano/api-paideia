<?php

namespace Tests\App\Http\Controllers;

use Tests\TestCase;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

# php artisan test --filter=StudentControllerTest
class StudentControllerTest extends TestCase
{
    # php artisan test --filter=StudentControllerTest::test_store
    public function test_store()
    {
        //* Crie um usuário fictício para autenticar
        $user = User::factory()->create();

        //* Gere o token JWT para o usuário
        $token = JWTAuth::fromUser($user);

        //* Fake
        $faker = Faker::create();
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson(route('students.store'), [

            'name'=> $faker->name,
            'email' => $faker->safeEmail(),
            'phone'=> $faker->numerify('(91)9####-####'),
            'cpf'=> \Misterioso013\Tools\CPF::cpfRandom(false),
            'rg'=> $faker->numerify('#######'),
            'expedient_body'=> 'SEGUP/PA',
            'rg'=> $faker->numerify('#######'),
            'nationality'=> $faker->country,
            'naturalness'=> $faker->city,
            'name_mother'=> $faker->name('female'),
            'birth_date'=> $faker->dateTimeBetween('-24 year', 'now')->format('Y-m-d'),
            'gender' => collect(['M', 'F'])->random()
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data',
            'message'
        ]);
    }
}
