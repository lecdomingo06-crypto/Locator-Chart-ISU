@auth
    @php
        $chatbotUser = auth()->user();
        $chatbotAccountKey = implode(':', array_filter([
            'campus-chatbot',
            'v2',
            $chatbotUser?->role,
            $chatbotUser?->getAuthIdentifier(),
            $chatbotUser?->student_id ?: $chatbotUser?->username,
        ], fn ($value) => filled($value)));
    @endphp

    <div class="campus-chatbot"
        data-campus-chatbot
        data-chatbot-endpoint="{{ route('chatbot.message') }}"
        data-chatbot-storage-key="{{ $chatbotAccountKey }}">
        <section class="campus-chatbot-panel" data-chatbot-panel aria-label="Campus assistant chat" aria-hidden="true">
            <header class="campus-chatbot-head">
                <div>
                    <span>Campus Assistant</span>
                    <strong>Ask about professor availability</strong>
                </div>
                <button type="button" class="campus-chatbot-close" data-chatbot-close aria-label="Close chat">×</button>
            </header>

            <div class="campus-chatbot-log" data-chatbot-log>
                <div class="campus-chatbot-message is-bot">
                    Hi. Ask me things like “King Nool next class” or “Who is available in CCSICT?”
                </div>
            </div>

            <form class="campus-chatbot-form" data-chatbot-form>
                <input type="text" data-chatbot-input name="message" maxlength="500" placeholder="Ask about a professor..." autocomplete="off" required>
                <button type="submit">Send</button>
            </form>
        </section>

        <button type="button" class="campus-chatbot-bubble" data-chatbot-toggle aria-label="Open campus assistant" aria-expanded="false">
            <span class="campus-chatbot-bubble-mark" aria-hidden="true">
                <svg viewBox="0 0 24 24">
                    <path d="M4.5 6.5A4.5 4.5 0 0 1 9 2h6a4.5 4.5 0 0 1 4.5 4.5v4A4.5 4.5 0 0 1 15 15h-2.5L8 19v-4A4.5 4.5 0 0 1 4.5 10.5v-4Z"></path>
                    <path d="M9 8h6"></path>
                    <path d="M9 11h4"></path>
                </svg>
            </span>
            <span class="campus-chatbot-bubble-text">AI</span>
        </button>
    </div>

    <style>
        .campus-chatbot {
            position: fixed;
            right: 22px;
            bottom: 22px;
            z-index: 9998;
            font-family: 'Outfit', 'Figtree', system-ui, sans-serif;
            color: #10291c;
        }

        .campus-chatbot *,
        .campus-chatbot *::before,
        .campus-chatbot *::after {
            box-sizing: border-box;
        }

        .campus-chatbot-bubble,
        .campus-chatbot-close,
        .campus-chatbot-form button {
            font: inherit;
            cursor: pointer;
        }

        .campus-chatbot-bubble {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            min-width: 64px;
            height: 60px;
            padding: 0 15px;
            border: 0;
            border-radius: 999px;
            color: #ffffff;
            background: linear-gradient(135deg, #188a52, #0b5b37);
            box-shadow: 0 18px 38px rgba(8, 57, 36, 0.28);
        }

        .campus-chatbot-bubble-mark {
            display: grid;
            place-items: center;
            width: 34px;
            height: 34px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.15);
        }

        .campus-chatbot-bubble svg {
            width: 21px;
            height: 21px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .campus-chatbot-bubble-text {
            font-size: 0.9rem;
            font-weight: 900;
            letter-spacing: 0.04em;
        }

        .campus-chatbot-panel {
            position: absolute;
            right: 0;
            bottom: 74px;
            display: grid;
            grid-template-rows: auto minmax(0, 1fr) auto auto;
            width: min(390px, calc(100vw - 28px));
            height: min(590px, calc(100vh - 118px));
            overflow: hidden;
            border-radius: 14px;
            border: 1px solid rgba(12, 92, 56, 0.16);
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 26px 70px rgba(8, 57, 36, 0.24);
            opacity: 0;
            transform: translateY(10px) scale(0.97);
            transform-origin: right bottom;
            pointer-events: none;
            transition: opacity 0.18s ease, transform 0.18s ease;
        }

        .campus-chatbot.is-open .campus-chatbot-panel {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        .campus-chatbot-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 16px 14px;
            color: #effcf3;
            background: linear-gradient(135deg, #0b5b37, #147247);
        }

        .campus-chatbot-head div {
            display: grid;
            gap: 3px;
            min-width: 0;
        }

        .campus-chatbot-head span {
            font-size: 0.72rem;
            font-weight: 900;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(239, 252, 243, 0.75);
        }

        .campus-chatbot-head strong {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 1rem;
        }

        .campus-chatbot-close {
            flex: 0 0 34px;
            width: 34px;
            height: 34px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
            font-size: 1.35rem;
            line-height: 1;
        }

        .campus-chatbot-log {
            display: grid;
            align-content: start;
            gap: 10px;
            min-height: 0;
            padding: 14px;
            overflow-y: auto;
            background: linear-gradient(180deg, #f6fbf7, #edf7f1);
        }

        .campus-chatbot-message {
            width: fit-content;
            max-width: 88%;
            padding: 10px 12px;
            border-radius: 12px;
            font-size: 0.92rem;
            line-height: 1.45;
            white-space: pre-line;
        }

        .campus-chatbot-message.is-bot {
            border-top-left-radius: 5px;
            background: #ffffff;
            border: 1px solid rgba(12, 92, 56, 0.1);
            box-shadow: 0 10px 22px rgba(8, 57, 36, 0.06);
        }

        .campus-chatbot-message.is-user {
            justify-self: end;
            border-top-right-radius: 5px;
            color: #ffffff;
            background: #147247;
        }

        .campus-chatbot-message.is-loading {
            color: #5d7064;
            font-style: italic;
        }

        .campus-chatbot-form {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 8px;
            padding: 12px;
            background: #ffffff;
            border-top: 1px solid rgba(12, 92, 56, 0.08);
        }

        .campus-chatbot-form input {
            width: 100%;
            min-height: 42px;
            padding: 0 12px;
            border-radius: 999px;
            border: 1px solid rgba(12, 92, 56, 0.18);
            outline: none;
            color: #10291c;
            background: #f8fbf8;
            font: inherit;
        }

        .campus-chatbot-form input:focus {
            border-color: rgba(20, 114, 71, 0.5);
            box-shadow: 0 0 0 4px rgba(20, 114, 71, 0.11);
        }

        .campus-chatbot-form button {
            min-height: 42px;
            padding: 0 15px;
            border: 0;
            border-radius: 999px;
            color: #ffffff;
            background: #0c5c38;
            font-size: 0.86rem;
            font-weight: 900;
        }

        .campus-chatbot-form button:disabled,
        .campus-chatbot-form input:disabled {
            cursor: not-allowed;
            opacity: 0.65;
        }

        @media (max-width: 520px) {
            .campus-chatbot {
                right: 14px;
                bottom: 14px;
            }

            .campus-chatbot-panel {
                bottom: 72px;
                width: calc(100vw - 28px);
                height: min(560px, calc(100vh - 96px));
            }

            .campus-chatbot-bubble {
                height: 56px;
                min-width: 56px;
                padding: 0 11px;
            }
        }
    </style>

    <script>
    (function () {
        const root = document.querySelector('[data-campus-chatbot]');

        if (!root || root.dataset.ready === 'true') {
            return;
        }

        root.dataset.ready = 'true';

        const endpoint = root.dataset.chatbotEndpoint;
        const storageKey = root.dataset.chatbotStorageKey;

        if (!storageKey) {
            return;
        }

        try {
            ['campus-chatbot:messages', 'campus-chatbot:open', 'campus-chatbot:draft'].forEach(function (legacyKey) {
                window.localStorage.removeItem(legacyKey);
            });
        } catch (error) {
            // Ignore storage failures.
        }

        const messagesKey = storageKey + ':messages';
        const openKey = storageKey + ':open';
        const draftKey = storageKey + ':draft';
        const panel = root.querySelector('[data-chatbot-panel]');
        const toggle = root.querySelector('[data-chatbot-toggle]');
        const closeButton = root.querySelector('[data-chatbot-close]');
        const form = root.querySelector('[data-chatbot-form]');
        const input = root.querySelector('[data-chatbot-input]');
        const log = root.querySelector('[data-chatbot-log]');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        function readStoredMessages() {
            try {
                const stored = JSON.parse(window.localStorage.getItem(messagesKey) || '[]');

                if (!Array.isArray(stored)) {
                    return [];
                }

                return stored.filter(function (message) {
                    return message && ['bot', 'user'].includes(message.type) && typeof message.text === 'string';
                }).slice(-40);
            } catch (error) {
                return [];
            }
        }

        function saveMessages() {
            try {
                const messages = Array.from(log.querySelectorAll('.campus-chatbot-message:not(.is-loading)'))
                    .map(function (message) {
                        return {
                            type: message.classList.contains('is-user') ? 'user' : 'bot',
                            text: message.textContent,
                        };
                    })
                    .slice(-40);

                window.localStorage.setItem(messagesKey, JSON.stringify(messages));
            } catch (error) {
                // Browser storage can be unavailable in private modes.
            }
        }

        function restoreMessages() {
            const storedMessages = readStoredMessages();

            if (!storedMessages.length) {
                saveMessages();
                return;
            }

            log.innerHTML = '';

            storedMessages.forEach(function (message) {
                addMessage(message.text, message.type, false);
            });
        }

        function setOpen(isOpen) {
            root.classList.toggle('is-open', isOpen);
            panel.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

            try {
                window.localStorage.setItem(openKey, isOpen ? '1' : '0');
            } catch (error) {
                // Ignore storage failures.
            }

            if (isOpen) {
                window.setTimeout(function () {
                    input.focus();
                }, 80);
            }
        }

        function addMessage(text, type, shouldPersist = true) {
            const message = document.createElement('div');
            message.className = 'campus-chatbot-message is-' + type;
            message.textContent = text;
            log.appendChild(message);
            log.scrollTop = log.scrollHeight;

            if (shouldPersist && !message.classList.contains('is-loading')) {
                saveMessages();
            }

            return message;
        }

        function setBusy(isBusy) {
            input.disabled = isBusy;
            form.querySelector('button').disabled = isBusy;
        }

        async function ask(text) {
            addMessage(text, 'user');
            setBusy(true);

            const loading = addMessage('Checking the live records...', 'bot is-loading', false);

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ message: text }),
                });

                const data = await response.json();

                loading.remove();

                if (!response.ok) {
                    addMessage(data.message || 'Sorry, I could not answer that right now.', 'bot');
                    return;
                }

                addMessage(data.answer || 'I could not find an answer for that yet.', 'bot');
            } catch (error) {
                loading.remove();
                addMessage('The assistant could not connect. Please try again.', 'bot');
            } finally {
                setBusy(false);
                input.focus();
            }
        }

        restoreMessages();

        try {
            input.value = window.localStorage.getItem(draftKey) || '';

            if (window.localStorage.getItem(openKey) === '1') {
                setOpen(true);
            }
        } catch (error) {
            // Ignore storage failures.
        }

        input.addEventListener('input', function () {
            try {
                window.localStorage.setItem(draftKey, input.value);
            } catch (error) {
                // Ignore storage failures.
            }
        });

        toggle.addEventListener('click', function () {
            setOpen(!root.classList.contains('is-open'));
        });

        closeButton.addEventListener('click', function () {
            setOpen(false);
        });

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const text = input.value.trim();

            if (!text) {
                return;
            }

            input.value = '';
            try {
                window.localStorage.removeItem(draftKey);
            } catch (error) {
                // Ignore storage failures.
            }
            ask(text);
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                setOpen(false);
            }
        });
    })();
    </script>
@endauth
