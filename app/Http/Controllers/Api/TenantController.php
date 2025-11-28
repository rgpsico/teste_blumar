<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TenantController extends Controller
{
    /**
     * Listar todos os inquilinos com paginação e busca
     */
    public function index(Request $request)
    {
        $query = Tenant::with(['user', 'property']);

        // Busca por nome, email ou documento do usuário vinculado
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('cpf', 'like', "%{$search}%");
            });
        }

        // Filtrar por propriedade
        if ($request->has('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        // Filtrar por status de pagamento
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $tenants = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json($tenants);
    }

    /**
     * Criar novo inquilino
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'property_id' => 'required|exists:properties,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'rent_amount' => 'required|numeric|min:0',
            'payment_status' => 'nullable|in:pending,paid,late',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        $tenant = Tenant::create($request->all());
        $tenant->load(['user', 'property']);

        return response()->json([
            'message' => 'Inquilino criado com sucesso!',
            'data' => $tenant
        ], 201);
    }

    /**
     * Exibir inquilino específico
     */
    public function show($id)
    {
        $tenant = Tenant::with(['user', 'property'])->find($id);

        if (!$tenant) {
            return response()->json([
                'message' => 'Inquilino não encontrado'
            ], 404);
        }

        return response()->json($tenant);
    }

    /**
     * Atualizar inquilino
     */
    public function update(Request $request, $id)
    {
        $tenant = Tenant::find($id);

        if (!$tenant) {
            return response()->json([
                'message' => 'Inquilino não encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'property_id' => 'required|exists:properties,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'rent_amount' => 'required|numeric|min:0',
            'payment_status' => 'nullable|in:pending,paid,late',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        $tenant->update($request->all());
        $tenant->load(['user', 'property']);

        return response()->json([
            'message' => 'Inquilino atualizado com sucesso!',
            'data' => $tenant
        ]);
    }

    /**
     * Excluir inquilino
     */
    public function destroy($id)
    {
        $tenant = Tenant::find($id);

        if (!$tenant) {
            return response()->json([
                'message' => 'Inquilino não encontrado'
            ], 404);
        }

        $tenant->delete();

        return response()->json([
            'message' => 'Inquilino excluído com sucesso!'
        ]);
    }
}
