<?php

namespace App\Http\Controllers;

use App\Models\BeachHouse;
use App\Http\Requests\StoreBeachHouseRequest;
use App\Http\Requests\UpdateBeachHouseRequest;

/**
 * @OA\OpenApi(
 * @OA\Info(
 * title="API de Casas de Praia",
 * version="1.0.0",
 * description="API para gerenciar casas de praia."
 * )
 * )
 */
class BeachHouseController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/beach-houses",
     * summary="Listar todas as casas de praia",
     * tags={"Beach Houses"},
     * @OA\Response(
     * response=200,
     * description="Lista de casas de praia",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="cor", type="string", example="Azul"),
     * @OA\Property(property="cidade", type="string", example="Rio de Janeiro"),
     * @OA\Property(property="nome", type="string", example="Casa da Praia Azul"),
     * @OA\Property(property="descritivo", type="string", example="Casa pé na areia, com piscina e churrasqueira."),
     * @OA\Property(property="foto1", type="string", example="https://servidor.com/casa1.jpg"),
     * @OA\Property(property="foto2", type="string", example=null),
     * @OA\Property(property="foto3", type="string", example=null),
     * @OA\Property(property="ativo", type="boolean", example=true)
     * )
     * )
     * )
     * )
     */
    public function index()
    {
        return response()->json(BeachHouse::all(), 200);
    }

    /**
     * @OA\Post(
     * path="/api/beach-houses",
     * summary="Criar uma nova casa de praia",
     * tags={"Beach Houses"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"cor","cidade","nome","descritivo"},
     * @OA\Property(property="cor", type="string", example="Azul"),
     * @OA\Property(property="cidade", type="string", example="Rio de Janeiro"),
     * @OA\Property(property="nome", type="string", example="Casa da Praia Azul"),
     * @OA\Property(property="descritivo", type="string", example="Casa pé na areia, com piscina e churrasqueira."),
     * @OA\Property(property="foto1", type="string", example="https://servidor.com/casa1.jpg"),
     * @OA\Property(property="foto2", type="string", example=null),
     * @OA\Property(property="foto3", type="string", example=null),
     * @OA\Property(property="ativo", type="boolean", example=true)
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Casa de praia criada com sucesso",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Casa de praia criada com sucesso!"),
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="cor", type="string", example="Azul"),
     * @OA\Property(property="cidade", type="string", example="Rio de Janeiro"),
     * @OA\Property(property="nome", type="string", example="Casa da Praia Azul"),
     * @OA\Property(property="descritivo", type="string", example="Casa pé na areia, com piscina e churrasqueira."),
     * @OA\Property(property="foto1", type="string", example="https://servidor.com/casa1.jpg"),
     * @OA\Property(property="foto2", type="string", example=null),
     * @OA\Property(property="foto3", type="string", example=null),
     * @OA\Property(property="ativo", type="boolean", example=true)
     * )
     * )
     * )
     * )
     */
    public function store(StoreBeachHouseRequest $request)
    {
        $house = BeachHouse::create($request->validated());

        return response()->json([
            'message' => 'Casa de praia criada com sucesso!',
            'data' => $house
        ], 201);
    }

    /**
     * @OA\Get(
     * path="/api/beach-houses/{id}",
     * summary="Obter detalhes de uma casa de praia",
     * tags={"Beach Houses"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID da casa de praia",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Detalhes da casa de praia",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="cor", type="string", example="Azul"),
     * @OA\Property(property="cidade", type="string", example="Rio de Janeiro"),
     * @OA\Property(property="nome", type="string", example="Casa da Praia Azul"),
     * @OA\Property(property="descritivo", type="string", example="Casa pé na areia, com piscina e churrasqueira."),
     * @OA\Property(property="foto1", type="string", example="https://servidor.com/casa1.jpg"),
     * @OA\Property(property="foto2", type="string", example=null),
     * @OA\Property(property="foto3", type="string", example=null),
     * @OA\Property(property="ativo", type="boolean", example=true)
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Casa de praia não encontrada",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Casa de praia não encontrada.")
     * )
     * )
     * )
     */
    public function show($id)
    {
        $house = BeachHouse::find($id);

        if (!$house) {
            return response()->json(['message' => 'Casa de praia não encontrada.'], 404);
        }

        return response()->json($house, 200);
    }

    /**
     * @OA\Put(
     * path="/api/beach-houses/{id}",
     * summary="Atualizar uma casa de praia",
     * tags={"Beach Houses"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID da casa de praia",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"cor","cidade","nome","descritivo"},
     * @OA\Property(property="cor", type="string", example="Azul"),
     * @OA\Property(property="cidade", type="string", example="Rio de Janeiro"),
     * @OA\Property(property="nome", type="string", example="Casa da Praia Azul"),
     * @OA\Property(property="descritivo", type="string", example="Casa pé na areia, com piscina e churrasqueira."),
     * @OA\Property(property="foto1", type="string", example="https://servidor.com/casa1.jpg"),
     * @OA\Property(property="foto2", type="string", example=null),
     * @OA\Property(property="foto3", type="string", example=null),
     * @OA\Property(property="ativo", type="boolean", example=true)
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Casa de praia atualizada com sucesso",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Casa de praia atualizada com sucesso!"),
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="cor", type="string", example="Azul"),
     * @OA\Property(property="cidade", type="string", example="Rio de Janeiro"),
     * @OA\Property(property="nome", type="string", example="Casa da Praia Azul"),
     * @OA\Property(property="descritivo", type="string", example="Casa pé na areia, com piscina e churrasqueira."),
     * @OA\Property(property="foto1", type="string", example="https://servidor.com/casa1.jpg"),
     * @OA\Property(property="foto2", type="string", example=null),
     * @OA\Property(property="foto3", type="string", example=null),
     * @OA\Property(property="ativo", type="boolean", example=true)
     * )
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Casa de praia não encontrada",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Casa de praia não encontrada.")
     * )
     * )
     * )
     */
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

    /**
     * @OA\Delete(
     * path="/api/beach-houses/{id}",
     * summary="Remover uma casa de praia",
     * tags={"Beach Houses"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID da casa de praia",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Casa de praia removida com sucesso",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Casa de praia removida com sucesso!")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Casa de praia não encontrada",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Casa de praia não encontrada.")
     * )
     * )
     * )
     */
    public function destroy($id)
    {
        $house = BeachHouse::find($id);

        if (!$house) {
            return response()->json(['message' => 'Casa de praia não encontrada.'], 404);
        }

        $house->delete();

        return response()->json(['message' => 'Casa de praia removida com sucesso!'], 200);
    }
}
