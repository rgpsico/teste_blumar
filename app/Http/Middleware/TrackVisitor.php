<?php

namespace App\Http\Middleware;

use App\Models\VisitorLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Para SPAs, rastrear apenas requisições GET específicas
            // Evita criar logs duplicados para cada chamada API
            $shouldTrack = false;

            // Rastrear rotas web (primeira carga da página)
            if ($request->is('/') || $request->is('login') || $request->is('register')) {
                $shouldTrack = true;
            }

            // Rastrear apenas certas rotas API (GET) que representam navegação
            if ($request->isMethod('GET') && $request->is('api/*')) {
                // Rastrear listagem de imóveis (navegação real do usuário)
                if ($request->is('api/properties') || $request->is('api/properties/*')) {
                    $shouldTrack = true;
                }
            }

            if ($shouldTrack) {
                // Evitar duplicatas: verificar se já registrou este IP na última hora para a mesma página
                $recentLog = VisitorLog::where('ip_address', $request->ip())
                    ->where('page_url', $request->fullUrl())
                    ->where('visited_at', '>=', now()->subHour())
                    ->exists();

                if (!$recentLog) {
                    VisitorLog::create([
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'page_url' => $request->fullUrl(),
                        'referrer' => $request->header('referer'),
                        'user_id' => auth()->id(),
                        'visited_at' => now(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Silenciosamente falhar para não afetar experiência do usuário
            \Log::error('Failed to log visitor: ' . $e->getMessage());
        }

        return $next($request);
    }
}
