<?php

namespace Tests\Feature\API\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\BaseTestCase;

class LoginFeatureTest extends BaseTestCase
{
    use RefreshDatabase;

    public function testAPILogin_WithValidCredentials_ShouldReturnSuccessResponse()
    {
        $response = $this->json('POST', route('auth.login'), [
            'email' => static::VALID_EMAIL,
            'personal_code' => static::VALID_CODE,
            'password' => static::VALID_PASSWORD,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'code'    => 200,
            'context' => 'login',
        ]);

        $response->assertJsonStructure([
            'data',
            'code',
            'app_message',
            'user_message',
            'context',
            'access_token',
        ]);
    }

    public function testAPILogin_WithInvalidPassword_ShouldReturnCredentialMismatchError()
    {
        $response = $this->json('POST', route('auth.login'), [
            'email' => static::VALID_EMAIL,
            'personal_code' => static::VALID_CODE,
            'password' => static::INVALID_PASSWORD,
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'code' => 403,
        ], true);
    }

    public function testAPILogin_WithNonExistentEmail_ShouldReturnCredentialMismatchError()
    {
        $response = $this->json('POST', route('auth.login'), [
            'email' => static::INVALID_EMAIL,
            'personal_code' => static::VALID_CODE,
            'password' => static::VALID_PASSWORD,
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'code' => 403,
        ], true);
    }

    public function testAPILogin_WithoutEmail_ShouldReturnEmailValidationError()
    {
        $response = $this->json('POST', route('auth.login'), [
            'personal_code' => static::VALID_CODE,
            'password' => static::VALID_PASSWORD,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function testAPILogin_WithoutPassword_ShouldReturnPasswordValidationError()
    {
        $response = $this->json('POST', route('auth.login'), [
            'email' => static::VALID_EMAIL,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password');
    }
}
