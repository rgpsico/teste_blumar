<?php


namespace App\Http\Controllers;

use App\Models\Imovel;
use App\Models\ImovelEndereco;
use App\Models\ImovelFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreImovelRequest;
use App\Http\Requests\UpdateImovelRequest;

class ImovelController extends Controller
{

    public function filter(Request $request)
    {



        $query = Imovel::query()->with('endereco', 'fotos');

        // Filtros opcionais
        if ($request->has('pais')) {
            $query->whereHas('endereco', function ($q) use ($request) {
                $q->where('pais', $request->pais);
            });
        }

        if ($request->has('bairro')) {
            $query->whereHas('endereco', function ($q) use ($request) {
                $q->where('bairro', $request->bairro);
            });
        }

        if ($request->has('cep')) {
            $query->whereHas('endereco', function ($q) use ($request) {
                $q->where('cep', $request->cep);
            });
        }

        if ($request->has('titulo')) {
            $query->where('titulo', 'like', '%' . $request->titulo . '%');
        }

        $imoveis = $query->get();

        return response()->json($imoveis);
    }


    // Listar imóveis do usuário logado
    public function index()
    {

        $imoveis = Auth::user()->imoveis()->with('endereco', 'fotos')->get();
        return response()->json($imoveis);
    }

    public function publicIndex(Request $request)
    {
        $query = Imovel::query()->with('endereco', 'fotos')->where('ativo', true);

        // Filtros opcionais
        if ($request->has('pais')) {
            $query->whereHas('endereco', function ($q) use ($request) {
                $q->where('pais', $request->pais);
            });
        }

        if ($request->has('bairro')) {
            $query->whereHas('endereco', function ($q) use ($request) {
                $q->where('bairro', $request->bairro);
            });
        }

        if ($request->has('cep')) {
            $query->whereHas('endereco', function ($q) use ($request) {
                $q->where('cep', $request->cep);
            });
        }

        if ($request->has('titulo')) {
            $query->where('titulo', 'like', '%' . $request->titulo . '%');
        }

        // Retorna todos os imóveis filtrados
        $imoveis = $query->get();

        return response()->json($imoveis);
    }

    // Visualizar imóvel específico
    public function show($id)
    {
        $imovel = Imovel::with('endereco', 'fotos')->findOrFail($id);
        return response()->json($imovel);
    }

    // Criar novo imóvel
    public function store(StoreImovelRequest $request)
    {
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

        return response()->json([
            'message' => 'Imóvel criado com sucesso',
            'imovel' => $imovel
        ], 201);
    }

    // Atualizar imóvel
    public function update(UpdateImovelRequest  $request, $id)
    {
        $imovel = Imovel::find($id);

        if ($imovel->user_id !== Auth::id()) {
            return response()->json(['message' => 'Não autorizado'], 403);
        }

        if (!$imovel) {
            return response()->json(['message' => 'Imóvel não encontrado'], 404);
        }



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

        if (!$imovel) {
            return response()->json(['message' => 'Imóvel não encontrado'], 404);
        }

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
