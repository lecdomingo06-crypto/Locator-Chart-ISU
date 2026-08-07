<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    public function createApplication(): Application
    {
        $configCache = dirname(__DIR__).'/bootstrap/cache/config.php';

        if (file_exists($configCache)) {
            throw new RuntimeException(
                'Refusing to run tests while Laravel configuration is cached. Run php artisan optimize:clear first.'
            );
        }

        $app = parent::createApplication();
        $connection = $app['config']->get('database.default');
        $database = $app['config']->get("database.connections.{$connection}.database");

        if (! $app->environment('testing') || $connection !== 'sqlite' || $database !== ':memory:') {
            throw new RuntimeException(
                'Refusing to run tests outside the in-memory SQLite testing database.'
            );
        }

        return $app;
    }
}