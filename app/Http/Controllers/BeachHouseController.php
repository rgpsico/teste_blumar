<?php

namespace App\Http\Controllers;

use App\Models\BeachHouse;
use App\Http\Requests\StoreBeachHouseRequest;
use App\Http\Requests\UpdateBeachHouseRequest;

class BeachHouseController extends Controller
{
    public function index()
    {
        return response()->json(BeachHouse::all(), 200);
    }

    public function show($id)
    {
        $house = BeachHouse::find($id);

        if (!$house) {
            return response()->json(['message' => 'Casa de praia não encontrada.'], 404);
        }

        return response()->json($house, 200);
    }

    public function store(StoreBeachHouseRequest $request)
    {

        $house = BeachHouse::create($request->validated());

        return response()->json([
            'message' => 'Casa de praia criada com sucesso!',
            'data' => $house
        ], 201);
    }

    public function update(UpdateBeachHouseRequest $request, $id)
    {
        $house = BeachHouse::find($id);

        if (!$house) {
            return response()->json(['message' => 'Casa de praia não encontrada.'], 404);
        }

        $house->update($request->validated());

        return response()->json([
            'message' => 'Casa de praia atualizada com sucesso!',
            'data' => $house
        ], 200);
    }
}
