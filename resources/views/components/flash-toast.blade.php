@props([
    'message' => null,
    'type' => 'success',
])

@php
    $sessionError = session('error');
    $toastMessage = $message ?? session('success') ?? session('status') ?? $sessionError;
    $toastType = $type === 'error' || ($message === null && $sessionError) ? 'error' : 'success';
@endphp

@if($toastMessage)
    <div class="flash-toast flash-toast-{{ $toastType }}" role="{{ $toastType === 'error' ? 'alert' : 'status' }}" aria-live="{{ $toastType === 'error' ? 'assertive' : 'polite' }}" data-flash-toast>
        <div class="flash-toast-mark" aria-hidden="true"></div>
        <div class="flash-toast-message">{{ __($toastMessage) }}</div>
    </div>

    <style>
        .flash-toast {
            position: fixed;
            top: 18px;
            right: 18px;
            z-index: 9999;
            display: grid;
            grid-template-columns: 10px minmax(0, 1fr);
            align-items: center;
            gap: 12px;
            width: min(390px, calc(100vw - 32px));
            padding: 14px 16px;
            border-radius: 8px;
            background: #ffffff;
            border: 1px solid #bddfc8;
            box-shadow: 0 18px 38px rgba(8, 57, 36, 0.18);
            color: #10291c;
            font: 700 0.94rem/1.45 'Outfit', 'Figtree', system-ui, sans-serif;
            animation: flashToastIn 0.22s ease-out;
        }

        .flash-toast-mark {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: #16a34a;
            box-shadow: 0 0 0 5px rgba(22, 163, 74, 0.12);
        }

        .flash-toast-success {
            background: #e8f6ed;
            border-color: #bddfc8;
            color: #07512d;
        }

        .flash-toast-error {
            background: #fff1f0;
            border-color: #ffd2cf;
            color: #b42318;
        }

        .flash-toast-error .flash-toast-mark {
            background: #dc2626;
            box-shadow: 0 0 0 5px rgba(220, 38, 38, 0.12);
        }

        .flash-toast-message {
            min-width: 0;
        }

        .flash-toast.is-hiding {
            opacity: 0;
            transform: translateY(-8px);
            transition: opacity 0.28s ease, transform 0.28s ease;
        }

        @keyframes flashToastIn {
            from {
                opacity: 0;
                transform: translateY(-10px) scale(0.96);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-flash-toast]').forEach(function (toast) {
            window.setTimeout(function () {
                toast.classList.add('is-hiding');
                window.setTimeout(function () {
                    toast.remove();
                }, 320);
            }, 6000);
        });
    });
    </script>
@endif
