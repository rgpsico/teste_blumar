<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OwnerController extends Controller
{
    /**
     * Listar todos os proprietários com paginação
     */
    public function index(Request $request)
    {
        $query = Owner::query();

        // Busca por nome, email ou documento
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('document', 'like', "%{$search}%");
            });
        }

        $owners = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json($owners);
    }

    /**
     * Criar novo proprietário
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:owners,email',
            'document' => 'required|string|unique:owners,document',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        $owner = Owner::create($request->all());

        return response()->json([
            'message' => 'Proprietário criado com sucesso!',
            'data' => $owner
        ], 201);
    }

    /**
     * Exibir proprietário específico
     */
    public function show($id)
    {
        $owner = Owner::find($id);

        if (!$owner) {
            return response()->json([
                'message' => 'Proprietário não encontrado'
            ], 404);
        }

        return response()->json($owner);
    }

    /**
     * Atualizar proprietário
     */
    public function update(Request $request, $id)
    {
        $owner = Owner::find($id);

        if (!$owner) {
            return response()->json([
                'message' => 'Proprietário não encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:owners,email,' . $id,
            'document' => 'required|string|unique:owners,document,' . $id,
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        $owner->update($request->all());

        return response()->json([
            'message' => 'Proprietário atualizado com sucesso!',
            'data' => $owner
        ]);
    }

    /**
     * Excluir proprietário
     */
    public function destroy($id)
    {
        $owner = Owner::find($id);

        if (!$owner) {
            return response()->json([
                'message' => 'Proprietário não encontrado'
            ], 404);
        }

        $owner->delete();

        return response()->json([
            'message' => 'Proprietário excluído com sucesso!'
        ]);
    }
}
