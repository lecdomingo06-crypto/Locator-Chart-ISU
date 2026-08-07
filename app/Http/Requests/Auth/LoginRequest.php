<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Services\HCaptchaService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'h-captcha-response' => ['required'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->hasTooManyLoginAttempts()) {
                $this->addThrottleError($validator);

                return;
            }

            if ($validator->errors()->any()) {
                $this->incrementLoginAttempts();

                return;
            }

            $token = $this->input('h-captcha-response');

            if (! $token) {
                $validator->errors()->add('h-captcha-response', 'Please complete the captcha.');
                $this->incrementLoginAttempts();

                return;
            }

            /** @var HCaptchaService $svc */
            $svc = app(HCaptchaService::class);
            [$ok] = $svc->verify($token, $this->ip());

            if (! $ok) {
                $validator->errors()->add('h-captcha-response', 'Captcha verification failed. Please try again.');
                $this->incrementLoginAttempts();
            }
        });
    }

    protected function incrementLoginAttempts(): void
    {
        foreach ($this->limiterKeys() as $key) {
            RateLimiter::hit($key, $this->decaySeconds());
        }
    }

    public function messages(): array
    {
        return [
            'h-captcha-response.required' => 'Please complete the robot check before logging in.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = (string) $this->input('username');
        $password = (string) $this->input('password');
        $remember = $this->boolean('remember');
        $user = $this->findUserForLogin($login);

        $authenticated = $user && Auth::attempt([
            'id' => $user->getKey(),
            'password' => $password,
        ], $remember);

        if (! $authenticated) {
            $this->incrementLoginAttempts();

            if ($this->hasTooManyLoginAttempts()) {
                throw ValidationException::withMessages([
                    'username' => trans('auth.throttle', [
                        'seconds' => $this->lockoutSeconds(),
                        'minutes' => ceil($this->lockoutSeconds() / 60),
                    ]),
                ]);
            }

            $remaining = $this->remainingAttempts();

            throw ValidationException::withMessages([
                'username' => 'Wrong username/student ID or password. ' . ($remaining > 0 ? "You have {$remaining} attempt(s) remaining." : 'Your next login attempt will be blocked.'),
            ]);
        }

        if (Auth::user()?->is_suspended) {
            Auth::guard('web')->logout();

            throw ValidationException::withMessages([
                'username' => 'This account is suspended. Please contact the administrator.',
            ]);
        }

        $this->clearLoginAttempts();

        $this->session()->regenerate();
    }

    protected function maxAttempts(): int
    {
        return 5;
    }

    protected function decaySeconds(): int
    {
        return 7200; // 2 hours
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! $this->hasTooManyLoginAttempts()) {
            return;
        }

        event(new Lockout($this));

        $seconds = $this->lockoutSeconds();

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        $login = Str::lower(trim((string) $this->input('username', '')));
        $user = $this->findUserForLogin($login);
        $identifier = $user ? (string) $user->getKey() : $login;

        return Str::transliterate('login-account|'.$identifier.'|'.$this->ip());
    }

    protected function browserThrottleKey(): string
    {
        return Str::transliterate('login-browser|'.$this->ip());
    }

    /**
     * @return array<int, string>
     */
    protected function limiterKeys(): array
    {
        return array_values(array_unique([
            $this->throttleKey(),
            $this->browserThrottleKey(),
        ]));
    }

    protected function hasTooManyLoginAttempts(): bool
    {
        foreach ($this->limiterKeys() as $key) {
            if (RateLimiter::tooManyAttempts($key, $this->maxAttempts())) {
                return true;
            }
        }

        return false;
    }

    protected function lockoutSeconds(): int
    {
        $seconds = 0;

        foreach ($this->limiterKeys() as $key) {
            if (RateLimiter::tooManyAttempts($key, $this->maxAttempts())) {
                $seconds = max($seconds, RateLimiter::availableIn($key));
            }
        }

        return $seconds > 0 ? $seconds : $this->decaySeconds();
    }

    protected function remainingAttempts(): int
    {
        $remaining = $this->maxAttempts();

        foreach ($this->limiterKeys() as $key) {
            $remaining = min($remaining, $this->maxAttempts() - RateLimiter::attempts($key));
        }

        return max(0, $remaining);
    }

    protected function clearLoginAttempts(): void
    {
        foreach ($this->limiterKeys() as $key) {
            RateLimiter::clear($key);
        }
    }

    protected function addThrottleError($validator): void
    {
        $seconds = $this->lockoutSeconds();

        $validator->errors()->add('username', trans('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
        ]));
    }

    protected function findUserForLogin(string $login): ?User
    {
        $login = Str::lower(trim($login));

        if ($login === '') {
            return null;
        }

        return User::query()
            ->whereRaw('LOWER(username) = ?', [$login])
            ->orWhereRaw('LOWER(student_id) = ?', [$login])
            ->first();
    }
}
