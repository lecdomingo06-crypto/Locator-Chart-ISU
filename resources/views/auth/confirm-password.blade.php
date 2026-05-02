<x-guest-layout>
    <div class="auth-stack">
        <div class="auth-heading">
            <div class="auth-eyebrow">Protected Access</div>
            <h1 class="auth-title">One last security check.</h1>
            <p class="auth-copy">{{ __('This is a protected area of the system. Please re-enter your password before continuing.') }}</p>
        </div>

        <div style="display: grid; gap: 18px;">
            <div style="display: flex; gap: 16px; align-items: flex-start; padding: 22px; border-radius: 24px; background: linear-gradient(135deg, #0c5c38, #147247); color: #eefcf2; box-shadow: 0 24px 40px rgba(15, 92, 56, 0.2);">
                <div style="display: grid; place-items: center; width: 52px; height: 52px; border-radius: 16px; background: rgba(255, 255, 255, 0.14); flex-shrink: 0;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 3L18.5 5.5V10.8C18.5 15.1 15.7 19.1 12 20.5C8.3 19.1 5.5 15.1 5.5 10.8V5.5L12 3Z" stroke="white" stroke-width="1.8" stroke-linejoin="round"/>
                        <path d="M9.7 11.9L11.3 13.5L14.8 10" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <div style="display: grid; gap: 8px;">
                    <strong style="font-size: 1.05rem; letter-spacing: -0.02em;">Protected session confirmation</strong>
                    <span style="color: rgba(238, 252, 242, 0.8); line-height: 1.7;">
                        Confirming your password helps keep sensitive account actions and private sections of the teacher tracking system secure.
                    </span>
                </div>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="auth-form-grid" style="padding: 24px; border-radius: 26px; background: rgba(255, 255, 255, 0.55); border: 1px solid rgba(255, 255, 255, 0.72); box-shadow: 0 16px 40px rgba(14, 76, 46, 0.08);">
                @csrf

                <div class="auth-group">
                    <x-input-label for="password" :value="__('Password')" class="auth-label" />

                    <x-text-input id="password" class="auth-field"
                        type="password"
                        name="password"
                        required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="auth-error" />
                </div>

                <button type="submit" class="auth-button">
                    {{ __('Confirm') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
