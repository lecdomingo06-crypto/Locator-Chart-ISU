@props([
    'for',
    'confirmation' => null,
    'suggest' => true,
])

<div
    class="password-strength-meter"
    data-password-strength
    data-password-target="{{ $for }}"
    @if($confirmation) data-password-confirmation="{{ $confirmation }}" @endif
    data-strength="empty"
>
    <div class="password-strength-meter__top">
        <span class="password-strength-meter__label" data-strength-label>Password strength</span>
    </div>
    <div class="password-strength-meter__bar" aria-hidden="true">
        <span data-strength-bar></span>
    </div>
    <p class="password-strength-meter__hint" data-strength-hint>
        Use at least 8 characters with letters and numbers.
    </p>
</div>

@once
    <style>
        .password-strength-meter {
            display: grid;
            gap: 8px;
            margin-top: 10px;
            color: #173f2e;
        }

        :where(.registration-form, .auth-form-grid, .password-grid, .form-grid) {
            align-items: start;
        }

        .password-strength-meter__top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .password-strength-meter__label {
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0;
            color: #607567;
        }

        .password-strength-meter__bar {
            height: 8px;
            overflow: hidden;
            border-radius: 999px;
            background: #e6eee9;
        }

        .password-strength-meter__bar span {
            display: block;
            width: 0;
            height: 100%;
            border-radius: inherit;
            background: #94a3b8;
            transition: width 180ms ease, background-color 180ms ease;
        }

        .password-strength-meter__hint {
            margin: 0;
            font-size: 12px;
            line-height: 1.45;
            color: #607567;
        }

        .password-strength-meter[data-strength="weak"] .password-strength-meter__label {
            color: #b42318;
        }

        .password-strength-meter[data-strength="weak"] .password-strength-meter__bar span {
            width: 34%;
            background: #dc2626;
        }

        .password-strength-meter[data-strength="good"] .password-strength-meter__label {
            color: #a15c07;
        }

        .password-strength-meter[data-strength="good"] .password-strength-meter__bar span {
            width: 68%;
            background: #d97706;
        }

        .password-strength-meter[data-strength="strong"] .password-strength-meter__label {
            color: #047857;
        }

        .password-strength-meter[data-strength="strong"] .password-strength-meter__bar span {
            width: 100%;
            background: #16a34a;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const assessPassword = (value) => {
                if (!value) {
                    return {
                        level: 'empty',
                        label: 'Password strength',
                        hint: 'Use at least 8 characters with letters and numbers.',
                    };
                }

                if (value === '12345678') {
                    return {
                        level: 'weak',
                        label: 'Weak password',
                        hint: '12345678 is not allowed. Use letters and numbers together.',
                    };
                }

                const hasLetters = /[A-Za-z]/.test(value);
                const hasNumbers = /\d/.test(value);
                const hasLower = /[a-z]/.test(value);
                const hasUpper = /[A-Z]/.test(value);
                let score = 0;

                if (value.length >= 8) score += 1;
                if (hasLetters) score += 1;
                if (hasNumbers) score += 1;
                if (value.length >= 12) score += 1;
                if (hasLower && hasUpper) score += 1;

                if (score >= 5) {
                    return {
                        level: 'strong',
                        label: 'Strong password',
                        hint: 'Strong password. This meets the letter and number rule.',
                    };
                }

                if (score >= 3) {
                    return {
                        level: 'good',
                        label: 'Good password',
                        hint: 'Accepted. Add more length or mixed case to make it stronger.',
                    };
                }

                return {
                    level: 'weak',
                    label: 'Weak password',
                    hint: 'Add at least 8 characters with letters and numbers.',
                };
            };

            const syncWidget = (widget, input) => {
                const result = assessPassword(input.value);
                const label = widget.querySelector('[data-strength-label]');
                const hint = widget.querySelector('[data-strength-hint]');

                widget.dataset.strength = result.level;

                if (label) {
                    label.textContent = result.label;
                }

                if (hint) {
                    hint.textContent = result.hint;
                }
            };

            document.querySelectorAll('[data-password-strength]').forEach((widget) => {
                const input = document.getElementById(widget.dataset.passwordTarget);

                if (!input) {
                    return;
                }

                input.addEventListener('input', () => syncWidget(widget, input));
                syncWidget(widget, input);
            });
        });
    </script>
@endonce
