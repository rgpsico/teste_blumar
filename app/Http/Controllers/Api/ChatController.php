<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyFaq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    // --- PARTE 1: FAQs (Gerenciamento) ---

    // Listar FAQs de um imóvel (Público)
    public function index(Request $request, $propertyId = null)
    {
        // Pega o ID da rota (/properties/{propertyId}/faqs) ou da query string (?property_id=...)
        $propertyId = $propertyId ?? $request->query('property_id');

        if (!$propertyId) {
            return response()->json(['error' => 'Property ID required'], 400);
        }

        return PropertyFaq::where('property_id', $propertyId)->get();
    }

    // Criar FAQ (Apenas Dono - Proteger na rota)
    public function store(Request $request, $propertyId)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        $property = Property::findOrFail($propertyId);

        if ($request->user()->id !== $property->owner_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $faq = PropertyFaq::create([
            'property_id' => $propertyId,
            'question' => $request->question,
            'answer' => $request->answer
        ]);

        return response()->json($faq, 201);
    }

    // Deletar FAQ
    public function destroy(Request $request, $id)
    {
        $faq = PropertyFaq::findOrFail($id);
        $property = Property::findOrFail($faq->property_id);

        if ($request->user()->id !== $property->owner_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $faq->delete();
        return response()->noContent();
    }

    // --- PARTE 2: INTELIGÊNCIA ARTIFICIAL ---

    public function askAi(Request $request, $propertyId)
    {
        // 1. Validar pergunta
        $request->validate(['message' => 'required|string']);

        // 2. Buscar dados do imóvel para dar contexto à IA
        $property = Property::with('community')->findOrFail($propertyId);
        $faqs = PropertyFaq::where('property_id', $propertyId)->get();

        // Montar o "Contexto do Sistema"
        $context = "Você é um assistente virtual útil e educado para um imóvel de aluguel. 
        Responda à pergunta do usuário APENAS com base nos dados abaixo. Se não souber, diga para contatar o anfitrião.
        
        Detalhes do Imóvel:
        - Nome: {$property->title}
        - Endereço: {$property->address}
        - Descrição: {$property->description}
        - Preço: R$ {$property->price}
        - Quartos: {$property->bedrooms}, Banheiros: {$property->bathrooms}
        - Condomínio: " . ($property->community ? $property->community->name : 'N/A') . "

        Perguntas frequentes fornecidas pelo proprietário:
        " . $faqs->map(fn ($faq) => "Q: {$faq->question}\nA: {$faq->answer}")->implode("\n\n") . "";

        // 3. Chamar DeepSeek API
        $response = Http::withToken(env('DEEP_SEEK_API_KEY'))
            ->post('https://api.deepseek.com/chat/completions', [
                'model' => 'deepseek-chat', // ou o modelo específico disponível
                'messages' => [
                    ['role' => 'system', 'content' => $context],
                    ['role' => 'user', 'content' => $request->message],
                ],
                'temperature' => 0.7,
            ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Erro ao contatar IA'], 500);
        }

        return response()->json([
            'reply' => $response->json('choices.0.message.content')
        ]);
    }
}
