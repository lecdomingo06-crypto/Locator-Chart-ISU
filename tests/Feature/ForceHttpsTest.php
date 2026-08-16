<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForceHttpsTest extends TestCase
{
    use RefreshDatabase;

    public function test_http_requests_redirect_to_https_when_enabled(): void
    {
        config(['app.force_https' => true]);

        $this->get('http://example.test/')
            ->assertMovedPermanently()
            ->assertRedirect('https://example.test');
    }

    public function test_localhost_is_not_redirected_when_https_is_enabled(): void
    {
        config(['app.force_https' => true]);

        $this->get('http://localhost/')
            ->assertOk();
    }
}
