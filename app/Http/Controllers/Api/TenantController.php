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
        $query = Tenant::with('property');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('document', 'like', "%{$search}%")
                    ->orWhere('contact', 'like', "%{$search}%");
            });
        }

        if ($request->has('property_id')) {
            $query->where('property_id', $request->property_id);
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
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'document' => 'nullable|string|max:50',
            'property_id' => 'required|exists:properties,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        $tenant = Tenant::create([
            ...$request->only(['name', 'contact', 'email', 'document', 'property_id', 'start_date', 'end_date', 'notes']),
            'user_id' => $request->user()->id,
        ]);
        $tenant->load('property');

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
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'document' => 'nullable|string|max:50',
            'property_id' => 'required|exists:properties,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        $tenant->update($request->only(['name', 'contact', 'email', 'document', 'property_id', 'start_date', 'end_date', 'notes']));
        $tenant->load('property');

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
