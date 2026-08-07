<?php

namespace App\Providers;

use Closure;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Password::defaults(fn () => Password::min(8)->letters()->numbers()->rules([
            function (string $attribute, mixed $value, Closure $fail): void {
                if ((string) $value === '12345678') {
                    $fail('The password 12345678 is not allowed. Please choose a stronger password.');
                }
            },
        ]));

        View::composer('layouts.guest', function ($view) {
            try {
                $view->with([
                    'guestSnapshotStaff' => User::snapshotStaff(),
                    'guestSnapshotTotals' => User::snapshotTotals(),
                ]);
            } catch (\Throwable) {
                $view->with([
                    'guestSnapshotStaff' => collect(),
                    'guestSnapshotTotals' => [
                        'professors' => 0,
                        'faculty' => 0,
                        'tracked' => 0,
                    ],
                ]);
            }
        });

        // login captcha replaced with hCaptcha; no per-view math question required
    }
}
