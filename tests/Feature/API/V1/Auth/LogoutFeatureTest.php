<?php

namespace Tests\Feature\API\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\BaseTestCase;

class LogoutFeatureTest extends BaseTestCase
{
    use RefreshDatabase;

    public function testAPILogout_WithInvalidToken_ShouldReturnUnauthorized()
    {
        $response = $this->json('POST', route('auth.logout'), [], [
            'Authorization' => 'Bearer ' . bin2hex(random_bytes(32)),
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testAPILogout_WithValidToken_ShouldReturnSuccessResponse()
    {
        $response = $this->json('POST', route('auth.logout'), [], [
            'Authorization' => 'Bearer ' . $this->customer['token'],
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'code'    => 200,
            'context' => 'logout',
        ]);
    }
}
