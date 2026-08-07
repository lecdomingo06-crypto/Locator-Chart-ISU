<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        RateLimiter::clear('login-browser|127.0.0.1');
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();
        Http::fake([
            'https://hcaptcha.com/siteverify' => Http::response(['success' => true], 200),
        ]);

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'password',
            'h-captcha-response' => 'test-token',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('student.viewer', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();
        Http::fake([
            'https://hcaptcha.com/siteverify' => Http::response(['success' => true], 200),
        ]);

        $this->post('/login', [
            'username' => $user->username,
            'password' => 'wrong-password',
            'h-captcha-response' => 'test-token',
        ]);

        $this->assertGuest();
    }

    public function test_users_get_throttle_notification_after_too_many_failed_logins(): void
    {
        $user = User::factory()->create();
        Http::fake([
            'https://hcaptcha.com/siteverify' => Http::response(['success' => true], 200),
        ]);

        for ($attempt = 1; $attempt <= 4; $attempt++) {
            $response = $this->from('/login')->post('/login', [
                'username' => $user->username,
                'password' => 'wrong-password',
                'h-captcha-response' => 'test-token',
            ]);

            $response->assertRedirect('/login');
            $this->assertGuest();
        }

        $response = $this->from('/login')->post('/login', [
            'username' => $user->username,
            'password' => 'wrong-password',
            'h-captcha-response' => 'test-token',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('username');
        $this->assertStringContainsString('too many login attempts', strtolower(session('errors')->first('username')));
        $this->assertGuest();
    }

    public function test_users_can_not_authenticate_with_invalid_captcha_answer(): void
    {
        $user = User::factory()->create();
        Http::fake([
            'https://hcaptcha.com/siteverify' => Http::response(['success' => false], 200),
        ]);

        $this->post('/login', [
            'username' => $user->username,
            'password' => 'password',
            'h-captcha-response' => 'invalid-token',
        ])->assertSessionHasErrors('h-captcha-response');

        $this->assertGuest();
    }

    public function test_login_attempts_increment_when_captcha_is_missing(): void
    {
        $user = User::factory()->create();

        for ($attempt = 1; $attempt <= 6; $attempt++) {
            $response = $this->from('/login')->post('/login', [
                'username' => $user->username,
                'password' => 'password',
            ]);

            if ($attempt < 6) {
                $response->assertSessionHasErrors('h-captcha-response');
            } else {
                $response->assertSessionHasErrors('username');
                $this->assertStringContainsString('2 hours', strtolower(session('errors')->first('username')));
            }

            $this->assertGuest();
        }
    }

    public function test_correct_credentials_are_blocked_while_rate_limited(): void
    {
        $user = User::factory()->create();
        Http::fake([
            'https://hcaptcha.com/siteverify' => Http::response(['success' => true], 200),
        ]);

        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $response = $this->from('/login')->post('/login', [
                'username' => $user->username,
                'password' => 'wrong-password',
                'h-captcha-response' => 'test-token',
            ]);

            if ($attempt < 5) {
                $response->assertRedirect('/login');
                $response->assertSessionHasErrors('username');
            } else {
                $response->assertRedirect('/login');
                $response->assertSessionHasErrors('username');
                $this->assertStringContainsString('too many login attempts', strtolower(session('errors')->first('username')));
            }

            $this->assertGuest();
        }

        $response = $this->from('/login')->post('/login', [
            'username' => $user->username,
            'password' => 'password',
            'h-captcha-response' => 'test-token',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('username');
        $this->assertStringContainsString('too many login attempts', strtolower(session('errors')->first('username')));
        $this->assertGuest();
    }

    public function test_rate_limit_applies_to_same_account_across_username_and_student_id(): void
    {
        $user = User::factory()->create(['student_id' => '2026-0000']);
        Http::fake([
            'https://hcaptcha.com/siteverify' => Http::response(['success' => true], 200),
        ]);

        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $response = $this->from('/login')->post('/login', [
                'username' => $user->username,
                'password' => 'wrong-password',
                'h-captcha-response' => 'test-token',
            ]);

            $response->assertRedirect('/login');
            $response->assertSessionHasErrors('username');
            $this->assertGuest();
        }

        $response = $this->from('/login')->post('/login', [
            'username' => $user->student_id,
            'password' => 'password',
            'h-captcha-response' => 'test-token',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('username');
        $this->assertStringContainsString('too many login attempts', strtolower(session('errors')->first('username')));
        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    private function loginCaptchaAnswer(): int
    {
        $this->markTestSkipped('Legacy math captcha removed; tests should use hCaptcha fakes.');
        return 0;
    }
}
