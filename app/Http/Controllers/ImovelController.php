<?php


namespace App\Http\Controllers;

use App\Models\Imovel;
use App\Models\ImovelEndereco;
use App\Models\ImovelFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImovelController extends Controller
{
    // Listar imóveis do usuário logado
    public function index()
    {
        $imoveis = Auth::user()->imoveis()->with('endereco', 'fotos')->get();
        return response()->json($imoveis);
    }

    // Visualizar imóvel específico
    public function show($id)
    {
        $imovel = Imovel::with('endereco', 'fotos')->findOrFail($id);
        return response()->json($imovel);
    }

    // Criar novo imóvel
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'preco' => 'required|numeric',
            'pais' => 'required|string',
            'cep' => 'required|string',
            'rua' => 'required|string',
            'numero' => 'required|string',
            'bairro' => 'nullable|string',
            'cidade' => 'nullable|string',
            'complemento' => 'nullable|string',
            'fotos.*' => 'nullable|image|max:2048', // múltiplas fotos
        ]);

        // Criar imóvel
        $imovel = Imovel::create([
            'user_id' => Auth::id(),
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'preco' => $request->preco,
        ]);

        // Criar endereço do imóvel
        ImovelEndereco::create([
            'imovel_id' => $imovel->id,
            'pais' => $request->pais,
            'cep' => $request->cep,
            'rua' => $request->rua,
            'numero' => $request->numero,
            'bairro' => $request->bairro,
            'cidade' => $request->cidade,
            'complemento' => $request->complemento,
        ]);

        // Salvar fotos
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $path = $foto->store('imoveis', 'public');
                ImovelFoto::create([
                    'imovel_id' => $imovel->id,
                    'path' => $path,
                ]);
            }
        }

        return response()->json(['message' => 'Imóvel criado com sucesso', 'imovel' => $imovel], 201);
    }

    // Atualizar imóvel
    public function update(Request $request, $id)
    {
        $imovel = Imovel::findOrFail($id);
        if ($imovel->user_id !== Auth::id()) {
            return response()->json(['message' => 'Não autorizado'], 403);
        }

        $request->validate([
            'titulo' => 'sometimes|required|string|max:255',
            'descricao' => 'sometimes|required|string',
            'preco' => 'sometimes|required|numeric',
            'pais' => 'sometimes|required|string',
            'cep' => 'sometimes|required|string',
            'rua' => 'sometimes|required|string',
            'numero' => 'sometimes|required|string',
            'bairro' => 'nullable|string',
            'cidade' => 'nullable|string',
            'complemento' => 'nullable|string',
            'fotos.*' => 'nullable|image|max:2048',
        ]);

        $imovel->update($request->only(['titulo', 'descricao', 'preco']));

        // Atualizar endereço
        $imovel->endereco()->update($request->only(['pais', 'cep', 'rua', 'numero', 'bairro', 'cidade', 'complemento']));

        // Salvar novas fotos
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $path = $foto->store('imoveis', 'public');
                ImovelFoto::create([
                    'imovel_id' => $imovel->id,
                    'path' => $path,
                ]);
            }
        }

        return response()->json(['message' => 'Imóvel atualizado com sucesso', 'imovel' => $imovel]);
    }

    // Deletar imóvel
    public function destroy($id)
    {
        $imovel = Imovel::findOrFail($id);
        if ($imovel->user_id !== Auth::id()) {
            return response()->json(['message' => 'Não autorizado'], 403);
        }

        // Deletar fotos do storage
        foreach ($imovel->fotos as $foto) {
            Storage::disk('public')->delete($foto->path);
        }

        $imovel->delete();

        return response()->json(['message' => 'Imóvel deletado com sucesso']);
    }
}
