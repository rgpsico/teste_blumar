<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CommunityController extends Controller
{
    /**
     * Listar todas as comunidades com paginação
     */
    public function index(Request $request)
    {
        $query = Community::query();

        // Busca por nome, cidade ou estado
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('state', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtrar por status (ativo/inativo)
        if ($request->has('active')) {
            $query->where('active', $request->active === 'true' || $request->active === '1');
        }

        $communities = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 12));

        return response()->json($communities);
    }

    /**
     * Criar nova comunidade
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:10',
            'image' => 'nullable|string', // Base64 ou URL
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Processar imagem se enviada em base64
        if ($request->has('image') && Str::startsWith($request->image, 'data:image')) {
            $data['image'] = $this->saveBase64Image($request->image);
        }

        $community = Community::create($data);

        return response()->json([
            'message' => 'Comunidade criada com sucesso!',
            'data' => $community
        ], 201);
    }

    /**
     * Exibir comunidade específica
     */
    public function show($id)
    {
        $community = Community::with(['users', 'properties'])->find($id);

        if (!$community) {
            return response()->json([
                'message' => 'Comunidade não encontrada'
            ], 404);
        }

        return response()->json($community);
    }

    /**
     * Atualizar comunidade
     */
    public function update(Request $request, $id)
    {
        $community = Community::find($id);

        if (!$community) {
            return response()->json([
                'message' => 'Comunidade não encontrada'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:10',
            'image' => 'nullable|string',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Processar nova imagem se enviada
        if ($request->has('image') && Str::startsWith($request->image, 'data:image')) {
            // Deletar imagem antiga se existir
            if ($community->image && Storage::disk('public')->exists($community->image)) {
                Storage::disk('public')->delete($community->image);
            }
            $data['image'] = $this->saveBase64Image($request->image);
        }

        $community->update($data);

        return response()->json([
            'message' => 'Comunidade atualizada com sucesso!',
            'data' => $community
        ]);
    }

    /**
     * Excluir comunidade
     */
    public function destroy($id)
    {
        $community = Community::find($id);

        if (!$community) {
            return response()->json([
                'message' => 'Comunidade não encontrada'
            ], 404);
        }

        // Deletar imagem se existir
        if ($community->image && Storage::disk('public')->exists($community->image)) {
            Storage::disk('public')->delete($community->image);
        }

        $community->delete();

        return response()->json([
            'message' => 'Comunidade excluída com sucesso!'
        ]);
    }

    /**
     * Salvar imagem em base64
     */
    private function saveBase64Image($base64String)
    {
        // Extrair dados da imagem
        preg_match('/^data:image\/(\w+);base64,/', $base64String, $matches);
        $extension = $matches[1] ?? 'png';
        $imageData = substr($base64String, strpos($base64String, ',') + 1);
        $imageData = base64_decode($imageData);

        // Gerar nome único
        $filename = 'communities/' . Str::random(40) . '.' . $extension;

        // Salvar no storage
        Storage::disk('public')->put($filename, $imageData);

        return $filename;
    }
}
