<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectPwaManifest
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->canInject($response)) {
            return $response;
        }

        $content = $this->injectHeadTags($response->getContent());
        $content = $this->injectServiceWorkerRegistration($content);

        $response->setContent($content);

        return $response;
    }

    private function injectHeadTags(string $content): string
    {
        $closingHeadPosition = stripos($content, '</head>');

        if ($closingHeadPosition === false) {
            return $content;
        }

        $headTags = [];

        if (! preg_match('/<meta\s+[^>]*name=["\']viewport["\']/i', $content)) {
            $headTags[] = '    <meta name="viewport" content="width=device-width, initial-scale=1">';
        }

        if (! str_contains($content, 'rel="manifest"')) {
            $headTags[] = '    <link rel="manifest" href="/manifest.json">';
            $headTags[] = '    <meta name="theme-color" content="#0f6b3d">';
            $headTags[] = '    <meta name="mobile-web-app-capable" content="yes">';
            $headTags[] = '    <meta name="apple-mobile-web-app-title" content="Prof Tracker">';
        }

        if (! str_contains($content, 'data-pwa-responsive-ui')) {
            $headTags[] = view('components.pwa-responsive-ui')->render();
        }

        if ($headTags === []) {
            return $content;
        }

        return substr($content, 0, $closingHeadPosition).
            implode("\n", $headTags)."\n".
            substr($content, $closingHeadPosition);
    }

    private function injectServiceWorkerRegistration(string $content): string
    {
        if (str_contains($content, "serviceWorker.register('/service-worker.js')")) {
            return $content;
        }

        $closingBodyPosition = strripos($content, '</body>');

        if ($closingBodyPosition === false) {
            return $content;
        }

        $script = <<<'HTML'
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/service-worker.js');
            });
        }
    </script>
HTML;

        return substr($content, 0, $closingBodyPosition).
            $script."\n".
            substr($content, $closingBodyPosition);
    }

    private function canInject(Response $response): bool
    {
        if (! method_exists($response, 'getContent') || $response->isRedirection()) {
            return false;
        }

        $contentType = $response->headers->get('Content-Type', '');

        return str_contains($contentType, 'text/html') || $contentType === '';
    }
}
