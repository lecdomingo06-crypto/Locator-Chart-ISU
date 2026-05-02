<x-guest-layout>
    <div class="auth-stack">
        <div class="auth-heading">
            <div class="auth-eyebrow">Verify Email</div>
            <h1 class="auth-title">Check your inbox</h1>
            <p class="auth-copy">
                {{ __('Thanks for signing up! Before getting started, please verify your email address by clicking the link we just sent you. If you did not receive the email, we can send another one.') }}
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="auth-status">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="auth-actions">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <button type="submit" class="auth-button">
                    {{ __('Resend Verification Email') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="auth-button-secondary">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
