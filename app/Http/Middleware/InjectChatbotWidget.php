<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectChatbotWidget
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! auth()->check() || ! $this->canInject($response)) {
            return $response;
        }

        $content = $response->getContent();

        if (str_contains($content, 'data-campus-chatbot')) {
            return $response;
        }

        $widget = view('components.chatbot-widget')->render();
        $closingBodyPosition = strripos($content, '</body>');

        if ($closingBodyPosition === false) {
            $response->setContent($content.$widget);

            return $response;
        }

        $response->setContent(
            substr($content, 0, $closingBodyPosition).
            $widget.
            substr($content, $closingBodyPosition)
        );

        return $response;
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
