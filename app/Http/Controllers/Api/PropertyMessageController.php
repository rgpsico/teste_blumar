<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PropertyMessage;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyMessageController extends Controller
{
    public function store(Request $request, $propertyId)
    {
        $request->validate([
            'visitor_name' => 'required|string|max:255',
            'visitor_email' => 'required|email|max:255',
            'visitor_phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        $property = Property::findOrFail($propertyId);

        $message = PropertyMessage::create([
            'property_id' => $property->id,
            'visitor_name' => $request->visitor_name,
            'visitor_email' => $request->visitor_email,
            'visitor_phone' => $request->visitor_phone,
            'message' => $request->message,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Mensagem enviada com sucesso!',
            'data' => $message,
        ], 201);
    }

    public function getOwnerMessages(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'owner') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Buscar mensagens de todos os imóveis do proprietário
        $messages = PropertyMessage::whereHas('property', function ($query) use ($user) {
            $query->where('owner_id', $user->id);
        })
            ->with('property:id,title,address,city')
            ->orderBy('read', 'asc') // Não lidas primeiro
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Contar não lidas
        $unreadCount = PropertyMessage::whereHas('property', function ($query) use ($user) {
            $query->where('owner_id', $user->id);
        })
            ->where('read', false)
            ->count();

        return response()->json([
            'messages' => $messages,
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead(Request $request, $messageId)
    {
        $user = $request->user();

        $message = PropertyMessage::whereHas('property', function ($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($messageId);

        $message->markAsRead();

        return response()->json([
            'message' => 'Mensagem marcada como lida',
            'data' => $message,
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $user = $request->user();

        PropertyMessage::whereHas('property', function ($query) use ($user) {
            $query->where('owner_id', $user->id);
        })
            ->where('read', false)
            ->update([
                'read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'message' => 'Todas as mensagens foram marcadas como lidas',
        ]);
    }

    public function deleteMessage(Request $request, $messageId)
    {
        $user = $request->user();

        $message = PropertyMessage::whereHas('property', function ($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->findOrFail($messageId);

        $message->delete();

        return response()->json([
            'message' => 'Mensagem excluída com sucesso',
        ]);
    }
}
