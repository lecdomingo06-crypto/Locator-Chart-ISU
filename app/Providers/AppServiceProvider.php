<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\View;
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
                        'teachers' => 0,
                        'faculty' => 0,
                        'tracked' => 0,
                    ],
                ]);
            }
        });
    }
}
