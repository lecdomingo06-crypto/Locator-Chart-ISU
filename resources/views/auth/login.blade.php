<x-guest-layout>
    <div class="auth-stack">
        <div class="auth-heading">
            <div class="auth-campus-pill">
                <span class="auth-campus-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 10.5 12 6l8 4.5"></path>
                        <path d="M6 10.5v7.5"></path>
                        <path d="M10 10.5v7.5"></path>
                        <path d="M14 10.5v7.5"></path>
                        <path d="M18 10.5v7.5"></path>
                        <path d="M4 18h16"></path>
                    </svg>
                </span>
                Isabela State University Cauayan Campus
            </div>
            <h1 class="auth-title">Log in</h1>
            <p class="auth-copy">Use your school account to view professor and faculty availability, room assignments, and current status from one place.</p>
        </div>

        <x-auth-session-status class="auth-status" :status="session('status')" />

        @php
            $loginError = $errors->first('username') ?: $errors->first('password') ?: $errors->first('h-captcha-response');
        @endphp

        @if ($loginError)
            <x-flash-toast :message="$loginError" type="error" />
        @endif

        <form method="POST" action="{{ route('login') }}" class="auth-form-grid">
            @csrf

            <div class="auth-group">
                <x-input-label for="username" :value="__('Username / Student ID')" class="auth-label" />
                <x-text-input id="username"
                    class="auth-field"
                    type="text"
                    name="username"
                    :value="old('username')"
                    required
                    autofocus
                    autocomplete="username" />
                <x-input-error :messages="$errors->get('username')" class="auth-error" />
            </div>

            <div class="auth-group">
                <div class="auth-helper-row">
                    <x-input-label for="password" :value="__('Password')" class="auth-label" />
                </div>

                <x-text-input id="password"
                    class="auth-field"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password" />
                <x-password-strength for="password" :suggest="false" />
                <x-input-error :messages="$errors->get('password')" class="auth-error" />
            </div>

            <div class="auth-group">
                <x-input-label class="auth-label">Robot verification</x-input-label>
                <x-hcaptcha-field error-class="auth-error" />
            </div>

            <div class="auth-check">
                <div class="auth-check" style="justify-content: flex-start;">
                    <input id="remember_me" type="checkbox" class="auth-check-input" name="remember">
                    <label for="remember_me" class="auth-check-label">{{ __('Remember me') }}</label>
                </div>
            </div>

            <button type="submit" class="auth-button">
                {{ __('Log in') }}
            </button>

            <div class="auth-footer auth-footer-login">
                <strong>Don't have an account?</strong>
                <span>Students can request an account online.</span>
                <a href="{{ route('student.register') }}" class="auth-inline-link">Student registration</a>
            </div>
        </form>
    </div>
</x-guest-layout>
