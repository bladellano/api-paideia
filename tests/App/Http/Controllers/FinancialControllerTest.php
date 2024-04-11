<?php

namespace Tests\App\Http\Controllers;

use Tests\TestCase;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

# php artisan test --filter=FinancialControllerTest
class FinancialControllerTest extends TestCase
{
    # php artisan test --filter=FinancialControllerTest::test_store
    public function test_store()
    {
        //* Crie um usuário fictício para autenticar
        $user = User::factory()->create();

        //* Gere o token JWT para o usuário
        $token = JWTAuth::fromUser($user);

        //* Fake
        $faker = Faker::create();
        
        $dueDate = $faker->dateTimeBetween('now', '+6 months');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson(route('financials.store'), [
            'value' => $faker->randomFloat(2, 50, 500),
            'due_date' => $dueDate->format('Y-m-d'),
            'paid' => $faker->boolean(70),
            'observations' => $faker->sentence,
            'gateway_response' => $faker->text,
            'payment_type' => \App\Models\PaymentType::inRandomOrder()->first()->id,
            'registration_id' => \App\Models\Registration::inRandomOrder()->first()->id,
            'service_type_id' => \App\Models\ServiceType::inRandomOrder()->first()->id,
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data',
            'message'
        ]);
    }
}
