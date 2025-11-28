<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VisitorLog;
use App\Models\RegistrationLog;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function getAdminAnalytics(Request $request)
    {
        // Verificar se é admin
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $period = $request->get('period', '7'); // dias

        // Estatísticas gerais
        $totalUsers = User::count();
        $totalProperties = Property::count();
        $totalVisitors = VisitorLog::where('visited_at', '>=', now()->subDays($period))->count();
        $uniqueVisitors = VisitorLog::where('visited_at', '>=', now()->subDays($period))
            ->distinct('ip_address')
            ->count('ip_address');

        // Novos registros
        $newRegistrations = RegistrationLog::where('registered_at', '>=', now()->subDays($period))
            ->with('user')
            ->orderBy('registered_at', 'desc')
            ->get();

        // Registros por dia (últimos N dias)
        $registrationsByDay = RegistrationLog::where('registered_at', '>=', now()->subDays($period))
            ->select(DB::raw('DATE(registered_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Visitantes por dia
        $visitorsByDay = VisitorLog::where('visited_at', '>=', now()->subDays($period))
            ->select(DB::raw('DATE(visited_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Registros por tipo de usuário
        $registrationsByType = RegistrationLog::where('registered_at', '>=', now()->subDays($period))
            ->select('user_type', DB::raw('count(*) as count'))
            ->groupBy('user_type')
            ->get();

        // Páginas mais visitadas
        $topPages = VisitorLog::where('visited_at', '>=', now()->subDays($period))
            ->select('page_url', DB::raw('count(*) as count'))
            ->groupBy('page_url')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'summary' => [
                'total_users' => $totalUsers,
                'total_properties' => $totalProperties,
                'total_visitors' => $totalVisitors,
                'unique_visitors' => $uniqueVisitors,
                'new_registrations_count' => $newRegistrations->count(),
            ],
            'new_registrations' => $newRegistrations,
            'charts' => [
                'registrations_by_day' => $registrationsByDay,
                'visitors_by_day' => $visitorsByDay,
                'registrations_by_type' => $registrationsByType,
            ],
            'top_pages' => $topPages,
        ]);
    }

    public function getVisitorLogs(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $logs = VisitorLog::with('user')
            ->orderBy('visited_at', 'desc')
            ->paginate(50);

        return response()->json($logs);
    }

    public function getRegistrationLogs(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $logs = RegistrationLog::with('user')
            ->orderBy('registered_at', 'desc')
            ->paginate(50);

        return response()->json($logs);
    }

    /**
     * Dashboard de Logs com filtros avançados
     */
    public function getLogsDashboard(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Filtros
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $userId = $request->get('user_id');
        $route = $request->get('route');

        // Query base
        $query = VisitorLog::whereBetween('visited_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        // Filtrar por usuário
        if ($userId) {
            $query->where('user_id', $userId);
        }

        // Filtrar por rota
        if ($route) {
            $query->where('page_url', 'like', "%{$route}%");
        }

        // Cards de resumo
        $visitasHoje = VisitorLog::whereDate('visited_at', now())->count();
        $visitasSemana = VisitorLog::whereBetween('visited_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $visitasMes = VisitorLog::whereMonth('visited_at', now()->month)
            ->whereYear('visited_at', now()->year)
            ->count();
        $visitasTotal = VisitorLog::count();

        // Dados para gráfico - acessos por dia
        $acessosPorDia = VisitorLog::whereBetween('visited_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->when($route, fn($q) => $q->where('page_url', 'like', "%{$route}%"))
            ->select(DB::raw('DATE(visited_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top usuários
        $topUsers = VisitorLog::whereBetween('visited_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereNotNull('user_id')
            ->select('user_id', DB::raw('count(*) as count'))
            ->with('user:id,name,email')
            ->groupBy('user_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // Top rotas
        $topRotas = VisitorLog::whereBetween('visited_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select('page_url', DB::raw('count(*) as count'))
            ->groupBy('page_url')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // Lista de logs com paginação
        $logs = $query->with('user:id,name,email')
            ->orderBy('visited_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'cards' => [
                'visitas_hoje' => $visitasHoje,
                'visitas_semana' => $visitasSemana,
                'visitas_mes' => $visitasMes,
                'visitas_total' => $visitasTotal,
            ],
            'grafico' => [
                'acessos_por_dia' => $acessosPorDia,
            ],
            'top_users' => $topUsers,
            'top_rotas' => $topRotas,
            'logs' => $logs,
        ]);
    }
}
