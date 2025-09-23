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
use Inertia\Inertia;

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
    public function update(UpdateImovelRequest $request, $id)
    {
        $imovel = Imovel::find($id);

        if (!$imovel) {
            return response()->json(['message' => 'Imóvel não encontrado'], 404);
        }

        if ($imovel->user_id !== Auth::id()) {
            return response()->json(['message' => 'Não autorizado'], 403);
        }

        $imovel->update($request->only(['titulo', 'descricao', 'preco']));

        $imovel->endereco()->updateOrCreate(
            ['imovel_id' => $imovel->id],
            [
                'pais' => $request->input('pais'),
                'cep' => $request->input('cep'),
                'rua' => $request->input('rua'),
                'numero' => $request->input('numero'),
                'bairro' => $request->input('bairro'),
                'cidade' => $request->input('cidade'),
                'complemento' => $request->input('complemento'),
            ]
        );

        // Recarrega o modelo do imóvel e seus relacionamentos para obter os dados atualizados do banco de dados
        $imovel->refresh();

        $fotos = $imovel->fotos->map(function ($foto) {
            return [
                'id' => $foto->id,
                'path' => asset('storage/' . $foto->path),
            ];
        })->toArray();

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $path = $foto->store('imoveis', 'public');
                $fotoModel = ImovelFoto::create([
                    'imovel_id' => $imovel->id,
                    'path' => $path,
                ]);
                $fotos[] = [
                    'id' => $fotoModel->id,
                    'path' => asset('storage/' . $path),
                ];
            }
        }

        return response()->json([
            'message' => 'Imóvel atualizado com sucesso',
            'imovel' => [
                'id' => $imovel->id,
                'user_id' => $imovel->user_id,
                'titulo' => $imovel->titulo,
                'descricao' => $imovel->descricao,
                'preco' => $imovel->preco,
                'endereco' => $imovel->endereco,
                'fotos' => $fotos,
            ],
        ]);
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

    public function home()
    {
        return Inertia::render('imoveis.index', [
            'user' => auth()->user(),
            'properties' => Imovel::all(), // ou Imovel::all()
        ]);
    }
}
