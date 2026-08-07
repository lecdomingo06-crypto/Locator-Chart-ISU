<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Psr\Log\LoggerInterface;

class HCaptchaService
{
    public function __construct(protected ?LoggerInterface $logger = null)
    {
    }

    /**
     * Verify an hCaptcha token with hcaptcha siteverify endpoint.
     * Returns [bool $success, array $body]
     */
    public function verify(string $token, ?string $ip = null): array
    {
        $secret = config('services.hcaptcha.secret');

        if (! $secret) {
            if ($this->logger) {
                $this->logger->warning('hcaptcha.missing_secret');
            }

            return [false, ['error' => 'missing_secret']];
        }

        try {
            $res = Http::asForm()
                ->timeout(5)
                ->post('https://hcaptcha.com/siteverify', [
                    'secret' => $secret,
                    'response' => $token,
                    'remoteip' => $ip,
                ]);

            $body = $res->json() ?? [];
            $success = ! empty($body['success']);

            return [$success, $body];
        } catch (\Throwable $e) {
            if ($this->logger) {
                $this->logger->error('hcaptcha.verify_exception', ['err' => $e->getMessage()]);
            }

            return [false, ['error' => 'network_error']];
        }
    }
}
