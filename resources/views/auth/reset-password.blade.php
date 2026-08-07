<x-guest-layout>
    <div class="auth-stack">
        <div class="auth-heading">
            <div class="auth-eyebrow">Set New Password</div>
            <h1 class="auth-title">Create a new password</h1>
            <p class="auth-copy">Choose a secure new password to restore access to your professor tracking account.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="auth-form-grid">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="auth-group">
                <x-input-label for="email" :value="__('Email')" class="auth-label" />
                <x-text-input id="email" class="auth-field" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="auth-error" />
            </div>

            <div class="auth-group">
                <x-input-label for="password" :value="__('Password')" class="auth-label" />
                <x-text-input id="password" class="auth-field" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="auth-error" />
            </div>

            <div class="auth-group">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="auth-label" />
                <x-text-input id="password_confirmation" class="auth-field"
                    type="password"
                    name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="auth-error" />
            </div>

            <button type="submit" class="auth-button">
                {{ __('Reset Password') }}
            </button>
        </form>
    </div>
</x-guest-layout>
