<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Adicionar TrackVisitor tanto para web quanto API
        // Para SPAs, precisamos rastrear nas rotas API também
        $middleware->web(append: [
            \App\Http\Middleware\TrackVisitor::class,
        ]);

        // Configurar middlewares da API
        $middleware->api(append: [
            \App\Http\Middleware\TrackVisitor::class, // Rastrear visitantes nas chamadas API
        ]);

        // Excluir rotas API da verificação CSRF
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
