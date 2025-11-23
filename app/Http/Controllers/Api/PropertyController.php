<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with(['owner', 'tenant', 'community']);

        if ($request->user()) {
            if ($request->user()->isOwner()) {
                $query->where('owner_id', $request->user()->id);
            } elseif ($request->user()->isTenant()) {
                $query->where('tenant_id', $request->user()->id);
            }
        } else {
            $query->where('active', true)->where('status', 'available');
        }

        return response()->json($query->paginate(12));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip_code' => 'required|string',
            'price' => 'required|numeric|min:0',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'area' => 'required|integer|min:0',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'nullable|string',
            'video_url' => 'nullable|string',
            'community_id' => 'nullable|exists:communities,id',
        ]);

        // Se não especificado community_id, usar a comunidade do proprietário
        $communityId = $request->community_id ?? $request->user()->community_id;

        $property = Property::create([
            ...$request->all(),
            'owner_id' => $request->user()->id,
            'community_id' => $communityId,
        ]);

        $property->load(['owner', 'tenant', 'community']);

        return response()->json($property, 201);
    }

    public function show(string $id)
    {
        $property = Property::with(['owner', 'tenant', 'community'])->findOrFail($id);
        return response()->json($property);
    }

    public function update(Request $request, string $id)
    {
        $property = Property::findOrFail($id);

        if ($request->user()->isOwner() && $property->owner_id !== $request->user()->id) {
            return response()->json(['message' => 'Não autorizado'], 403);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'address' => 'sometimes|string',
            'city' => 'sometimes|string',
            'state' => 'sometimes|string',
            'zip_code' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'bedrooms' => 'sometimes|integer|min:0',
            'bathrooms' => 'sometimes|integer|min:0',
            'area' => 'sometimes|integer|min:0',
            'photos' => 'sometimes|array|max:5',
            'photos.*' => 'nullable|string',
            'video_url' => 'sometimes|nullable|string',
            'community_id' => 'sometimes|nullable|exists:communities,id',
            'status' => 'sometimes|in:available,rented,maintenance',
            'active' => 'sometimes|boolean',
        ]);

        $property->update($request->all());
        $property->load(['owner', 'tenant', 'community']);

        return response()->json($property);
    }

    public function destroy(string $id)
    {
        $property = Property::findOrFail($id);

        if (!request()->user()->isAdmin() && $property->owner_id !== request()->user()->id) {
            return response()->json(['message' => 'Não autorizado'], 403);
        }

        $property->delete();

        return response()->json(['message' => 'Imóvel excluído com sucesso']);
    }
}
