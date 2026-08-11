<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PwaAssetsTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_include_manifest_and_service_worker_registration(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<meta name="viewport" content="width=device-width, initial-scale=1">', false)
            ->assertSee('<link rel="manifest" href="/manifest.json">', false)
            ->assertSee('data-pwa-responsive-ui', false)
            ->assertSee("navigator.serviceWorker.register('/service-worker.js')", false);
    }

    public function test_manifest_and_service_worker_files_are_public(): void
    {
        $this->assertFileExists(public_path('manifest.json'));
        $this->assertFileExists(public_path('service-worker.js'));
        $this->assertFileExists(public_path('offline.html'));
        $this->assertFileExists(public_path('icons/icon-192.png'));
        $this->assertFileExists(public_path('icons/icon-512.png'));

        $manifest = json_decode(file_get_contents(public_path('manifest.json')), true, 512, JSON_THROW_ON_ERROR);

        $this->assertSame('Professor Tracking System', $manifest['name']);
        $this->assertContains('/icons/icon-192.png', array_column($manifest['icons'], 'src'));
        $this->assertContains('/icons/icon-512.png', array_column($manifest['icons'], 'src'));
        $this->assertStringContainsString('CACHE_VERSION', file_get_contents(public_path('service-worker.js')));
    }
}
