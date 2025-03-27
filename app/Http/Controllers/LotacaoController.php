<?php

namespace App\Http\Controllers;

use App\Models\Lotacao;
use Illuminate\Http\Request;

class LotacaoController extends Controller
{
    /**
     *  @OA\GET(
     *      path="/api/lotacao",
     *      summary="GET Lotação",
     *      description="GET Paginado",
     *      tags={"Lotação"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page Number",
     *         required=false,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      security={{"bearerAuth":{}}}
     *
     *  )
     */
    public function index()
    {
        $lotacao = Lotacao::with('unidade')->paginate(15);
        if(!$lotacao)
        {
            return response('Não encontrado', 404)->json();
        }
        return response()->json(['message' => 'Lotações encontradas', 'lotacao' => $lotacao]);
    }

    /**
     * @OA\Post(
     *     path="/api/store-lotacao",
     *     summary="Cria um novo lotação",
     *     description="Endpoint para cadasatrar uma nova lotação no sistema.",
     *     tags={"Lotação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"lot_id","pes_id","unid_id","lot_data_lotacao","lot_data_remocao","lot_portaria"},
     *             @OA\Property(property="lot_id", type="integer", example=1),
     *             @OA\Property(property="pes_id", type="integer", example="1"),
     *             @OA\Property(property="unid_id", type="integer", example="1"),
     *             @OA\Property(property="lot_data_lotacao", type="string", format="date", example="2024-05-15"),
     *             @OA\Property(property="lot_data_remocao", type="string", format="date", example="2025-05-15"),
     *             @OA\Property(property="lot_portaria", type="string", example="Portaria x1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Lotação Cadastrada com sucesso!",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lotação Cadastrada com sucesso!"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro na validação dos dados",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro de validação"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     *
     * )
     */
    public function store(Request $request)
    {
        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        $valited = $request->validate([
            'lot_id' => 'required|integer',
            'pes_id' => 'required|integer',
            'unid_id' => 'required|integer',
            'lot_data_lotacao' => 'required|date',
            'lot_data_remocao' => 'required|date',
            'lot_portaria' => 'required|string',
        ]);

        $lotacao = Lotacao::where('lot_id', $request->lot_id)->first();
        if (!$lotacao) {
            $lotacao = Lotacao::create($valited);
        }

        return response()->json(['message' => 'Lotação cadastrada', 'lotacao' => $lotacao]);
    }

    /**
     *  @OA\GET(
     *      path="/api/show-lotacao/{lot_id}",
     *      summary="Show Lotação",
     *      description="Show Lotação",
     *      tags={"Lotação"},
     *     @OA\Parameter(
     *         name="lot_id",
     *         in="path",
     *         required=true,
     *         description="ID da Lotação",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Lotação Encontrada",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Lotação não encontrada"
     *      ),
     *     security={{"bearerAuth":{}}}
     *  )
     */
    public function show(string $lot_id)
    {
        $lotacao = Lotacao::with(['pessoa' ,'unidade'])->where('lot_id', $lot_id)->first();

        if (!$lotacao) {
            return response('Não encontrado', 404)->json();
        }
        return response()->json(['message' => 'Lotação encontrada', 'lotacao' => $lotacao]);
    }

    /**
     * @OA\PUT(
     *     path="/api/update-lotacao/{lot_id}",
     *     summary="Atualizar uma Lotação",
     *     description="Endpoint para atualizar uma nova Lotação",
     *     tags={"Lotação"},
     *     @OA\Parameter(
     *         name="lot_id",
     *         in="path",
     *         required=true,
     *         description="ID da Lotação",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="pes_id", type="integer", example="1"),
     *             @OA\Property(property="unid_id", type="integer", example="1"),
     *             @OA\Property(property="lot_data_lotacao", type="string", format="date", example="2024-05-15"),
     *             @OA\Property(property="lot_data_remocao", type="string", format="date", example="2025-05-15"),
     *             @OA\Property(property="lot_portaria", type="string", example="Portaria x1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lotação atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lotação atualizado com sucesso"),
     *             @OA\Property(property="data", type="object",
     *             @OA\Property(property="pes_id", type="integer", example="1"),
     *             @OA\Property(property="unid_id", type="integer", example="1"),
     *             @OA\Property(property="lot_data_lotacao", type="string", format="date", example="2024-05-15"),
     *             @OA\Property(property="lot_data_remocao", type="string", format="date", example="2025-05-15"),
     *             @OA\Property(property="lot_portaria", type="string", example="Portaria x1")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro na requisição"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function update(Request $request, string $lot_id)
    {

        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        $valited = $request->validate([
            'pes_id' => 'integer',
            'unid_id' => 'integer',
            'lot_data_lotacao' => 'date',
            'lot_data_remocao' => 'date',
            'lot_portaria' => 'string',
        ]);

        $lotacao = Lotacao::where('lot_id', $lot_id)->first();
        if (!$lotacao) {
            return response('Error', 404)->json(['message' => 'Lotação não encontrada']);
        } else {
            $lotacao->update($valited);
        }

        return response()->json(['message' => 'Lotação atualizada', 'lotacao' => $lotacao]);
    }

    /**
     *  @OA\DELETE(
     *      path="/api/delete-lotacao/{lot_id}",
     *      summary="Deleta Lotação",
     *      description="Remove Lotação",
     *      tags={"Lotação"},
     *     @OA\Parameter(
     *         name="lot_id",
     *         in="path",
     *         required=true,
     *         description="ID do Lotação",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Lotação Removida",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Lotação não encontrada"
     *      ),
     *     security={{"bearerAuth":{}}}
     *  )
     */
    public function destroy(string $lot_id)
    {
        $lotacao = Lotacao::where('lot_id', $lot_id)->first();
        if (!$lotacao) {
            return response('Error', 404)->json(['message' => 'Lotação não encontrada']);
        } else {
            $lotacao->delete();
        }
        return response()->json(['message' => 'Lotação Removida', 'lotacao' => $lotacao]);
    }
}
