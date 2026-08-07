@props(['errorClass' => 'auth-error'])

@php
    $siteKey = config('services.hcaptcha.sitekey');
@endphp

<div class="captcha-wrap" data-captcha-wrap>
    @if ($siteKey)
        <div class="h-captcha" data-sitekey="{{ $siteKey }}" data-theme="light"></div>
        <p class="captcha-status" data-captcha-status>Loading robot check...</p>
    @else
        <p class="captcha-status is-error" data-captcha-status>
            Robot verification is not configured. Add HCAPTCHA_SITEKEY and HCAPTCHA_SECRET in the .env file.
        </p>
    @endif
</div>

@error('h-captcha-response')
    <div class="{{ $errorClass }}">{{ $message }}</div>
@enderror
