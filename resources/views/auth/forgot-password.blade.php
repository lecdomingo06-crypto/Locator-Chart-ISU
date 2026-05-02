<x-guest-layout>
    <div class="auth-stack">
        <div class="auth-heading">
            <div class="auth-eyebrow">Password Reset</div>
            <h1 class="auth-title">Forgot your password?</h1>
            <p class="auth-copy">
                {{ __('No problem. Enter your email address and we will send a password reset link so you can choose a new one.') }}
            </p>
        </div>

        <x-auth-session-status class="auth-status" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="auth-form-grid">
            @csrf

            <div class="auth-group">
                <x-input-label for="email" :value="__('Email')" class="auth-label" />
                <x-text-input id="email" class="auth-field" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="auth-error" />
            </div>

            <button type="submit" class="auth-button">
                {{ __('Email Password Reset Link') }}
            </button>
        </form>

        @if (Route::has('login'))
            <div class="auth-footer">
                <span>Remembered your password?</span>
                <a href="{{ route('login') }}" class="auth-inline-link">Log in</a>
            </div>
        @endif
    </div>
</x-guest-layout>
