<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_authenticate_user_and_generate_access_token()
    {

       User::factory(User::class)->create([
            'email' => 'user@example.com',
            'password' => bcrypt('secret')
       ]);


        $response = $this->postJson('api/V1/login', [
            'email' => 'user@example.com',
            'password' => 'secret'
        ]);


        $response->assertStatus(200);


        $response->assertJson([
            'message' => 'Authorized!'
        ]);


        $response->assertJsonStructure([
            'message',
            'status',
            'data' => [
                'token'
            ]
        ]);


        $this->assertTrue(Auth::check());
    }

    /** @test */
    public function it_returns_not_authorized_for_invalid_credentials()
    {

        $response = $this->postJson('api/V1/login', [
            'email' => 'invalid@example.com',
            'password' => 'password'
        ]);


        $response->assertStatus(403);


        $response->assertJson([
            'message' => 'Not Authorized!'
        ]);


        $this->assertFalse(Auth::check());
    }

    /** @test */
    public function it_can_logout_user_and_revoke_access_token()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('secret')
        ]);

        $token = $user->createToken('Test Token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('api/V1/logout');

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Token Revoked'
        ]);

        $this->assertFalse($user->tokens()->exists());
    }
}
