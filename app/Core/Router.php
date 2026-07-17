<?php

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $base = $scriptDir === '/' ? '' : rtrim($scriptDir, '/');
        if ($base !== '' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base)) ?: '/';
        }

        $handler = $this->routes[$method][$path] ?? null;

        if (!$handler) {
            http_response_code(404);
            view('errors/404', ['title' => 'Page not found']);
            return;
        }

        [$controller, $action] = $handler;
        (new $controller())->$action();
    }
}