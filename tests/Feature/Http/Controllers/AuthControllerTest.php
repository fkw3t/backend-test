<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_shouldnt_register_with_invalid_provider(): void
    {
        // arrange / act
        $response = $this->post('/api/register/invalidprovider', [
            'name' => 'Generic Name',
            'person_type' => 'fisical',
            'document_id' => '12345678901',
            'email' => 'mail@mail.com',
            'password' => '123',
        ]);

        // assert
        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Invalid provider',
            ]);
    }

    public function test_user_shouldnt_register_two_times(): void
    {
        // arrange
        $user = User::factory()->create();

        // act
        $response = $this->post('/api/register/user', [
            'name' => $user->name,
            'person_type' => $user->person_type,
            'document_id' => $user->document_id,
            'email' => $user->email,
            'password' => '123',
        ]);

        // assert
        $response->assertStatus(422);
    }

    # todo: transfer like invalid credentials test to data providers
    public function test_user_shouldnt_register_with_invalid_credentials(): void
    {
        // arrange/act
        $response = $this->post('/api/register/user', [
            'person_type' => 'fisical',
            'document_id' => '12345678901',
            'password' => '123',
        ]);

        // assert
        $response->assertStatus(422);
    }

    public function test_user_shouldnt_register_with_invalid_person_type(): void
    {
        // arrange/act
        $response = $this->post('/api/register/user', [
            'name' => 'Generic Name',
            'person_type' => 'invalid person type',
            'document_id' => '12345678901',
            'email' => 'mail@mail.com',
            'password' => '123',
        ]);

        // assert
        $response->assertStatus(422);
    }

    public function test_user_shouldnt_register_with_invalid_document_id(): void
    {
        // arrange/act
        $response = $this->post('/api/register/user', [
            'name' => 'Generic Name',
            'person_type' => 'fisical',
            'document_id' => '123456789012',
            'email' => 'mail@mail.com',
            'password' => '123',
        ]);

        // assert
        $response->assertStatus(422);
    }

    public function test_legal_person_shouldnt_register_with_cpf(): void
    {
        // arrange/act
        $response = $this->post('/api/register/user', [
            'name' => 'Generic Name',
            'person_type' => 'legal',
            'document_id' => '12345678901',
            'email' => 'mail@mail.com',
            'password' => '123',
        ]);

        // assert
        $response->assertStatus(422)
            ->assertJson([
                'error' => [
                    'If you are a legal person you must send a CNPJ not a CPF'
                ]
            ]);
    }

    public function test_fisical_person_shouldnt_register_with_cnpj(): void
    {
        $response = $this->post('/api/register/user', [
            'name' => 'Generic Name',
            'person_type' => 'fisical',
            'document_id' => '12345678901234',
            'email' => 'mail@mail.com',
            'password' => '123',
        ]);

        // assert
        $response->assertStatus(422)
            ->assertJson([
                'error' => [
                    'If you are a fisical person you must send a CPF not a CNPJ'
                ]
            ]);
    }

    public function test_user_shouldnt_login_with_invalid_credentials(): void
    {
        // arrange/act
        $response = $this->post('/api/login/user', [
            'email' => 'invalidmail@mail',
            'password' => '123',
        ]);

        // assert
        $response->assertStatus(401)
            ->assertJson([ 'message' => 'Invalid credentials']);
    }

    public function test_user_shouldnt_login_with_invalid_provider(): void
    {
        // arrange
        $user = User::factory()->create();

        // act
        $response = $this->post('/api/login/invalidprovider', [
            'email' => $user->email,
            'password' => '123',
        ]);

        // assert
        $response->assertStatus(422)
            ->assertJson([ 'message' => 'Invalid provider']);
    }

    public function dataProviderLogin(): array
    {
        $this->refreshApplication();

        $user = User::factory()->create();
        $seller = Seller::factory()->create();

        return [
            [ [ 
                'provider' => 'user',
                'credentials' => ['email' => $user->email, 'password' => '123'] 
            ] ],
            [ [ 
                'provider' => 'seller',
                'credentials' => ['email' => $seller->email, 'password' => '123'] 
            ] ],
        ];
    }

    # todo: use data provider to both providers
    public function test_user_shouldnt_login_with_success(): void
    {
        // arrange
        $user = User::factory()->create();

        // act
        $response = $this->post('/api/login/user', [
            'email' => $user->email,
            'password' => '123',
        ]);

        // assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in'
            ]);
    }

    # todo: use data provider to both providers
    public function test_seller_should_login_with_success(): void
    {
        // arrange
        $seller = Seller::factory()->create();

        // act
        $response = $this->post('/api/login/seller', [
            'email' => $seller->email,
            'password' => '123',
        ]);

        // assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in'
            ]);
    }

    public function test_seller_should_logout(): void
    {
        // arrange
        $provider = 'seller';
        $seller = Seller::factory()->create();
        Auth::guard($provider)->attempt([
            'email' => $seller->email,
            'password' => '123',
        ]);

        // act
        $response = $this->post("/api/logout/{$provider}");

        // assert
        $response->assertStatus(200)
            ->assertJson([ 'message' => 'Successfully logged out' ]);
    }
}
