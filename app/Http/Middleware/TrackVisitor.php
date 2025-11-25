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
        // Registrar visitante de forma assíncrona (não bloquear request)
        try {
            VisitorLog::create([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'page_url' => $request->fullUrl(),
                'referrer' => $request->header('referer'),
                'user_id' => auth()->id(),
                'visited_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Silenciosamente falhar para não afetar experiência do usuário
            \Log::error('Failed to log visitor: ' . $e->getMessage());
        }

        return $next($request);
    }
}
