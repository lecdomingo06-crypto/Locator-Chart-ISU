<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('mail:test {to : Email address that should receive the test message}', function (string $to) {
    try {
        Mail::html(
            '<h2>Professor Tracking System Email Test</h2><p>Real email delivery is working.</p>',
            function ($message) use ($to) {
                $message->to($to)
                    ->subject('Professor Tracking System Email Test');
            }
        );
    } catch (Throwable $exception) {
        $this->error('Test email failed: '.$exception->getMessage());

        return 1;
    }

    $this->info('Test email sent to '.$to.'.');

    return 0;
})->purpose('Send a real test email through the configured mailer');
