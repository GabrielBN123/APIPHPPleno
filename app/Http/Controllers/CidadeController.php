<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class CidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     *  @OA\GET(
     *      path="/api/cidade",
     *      summary="GET cidades",
     *      description="GET Paginado",
     *      tags={"Cidade"},
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
     *  )
     */
    public function index()
    {
        $cidade = Cidade::paginate(15);
        if ($cidade) {
            return response()->json([
                'message' => 'Registros encontrados',
                'cidades' => $cidade,
            ]);
        }
        return response()->json(['message' => 'Nenhum registro encontrado']);
    }

    /**
     * @OA\Post(
     *     path="/api/store-cidade",
     *     summary="Cria uma nova Cidade",
     *     description="Endpoint para criar uma nova cidade no sistema.",
     *     tags={"Cidade"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"cid_id", "cid_nome", "cid_uf"},
     *             @OA\Property(property="cid_id", type="integer", example=1),
     *             @OA\Property(property="cid_nome", type="string", example="Cuiabá"),
     *             @OA\Property(property="cid_uf", type="string", example="MT")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cidade criada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cidade criada com sucesso")
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
            'cid_id' => 'required|integer',
            'cid_nome' => 'required|string',
            'cid_uf' => 'required|string',
        ]);

        $cidade = Cidade::where('cid_id', $request->cid_id)->first();
        if (!$cidade) {
            $cidade = Cidade::create($valited);
        }

        return response()->json(['message' => 'Cidade cadastrada', 'cidade' => $cidade]);
    }

    /**
     *  @OA\GET(
     *      path="/api/show-cidade/{cid_id}",
     *      summary="Show Cidade",
     *      description="Retorna Cidade",
     *      tags={"Cidade"},
     *     @OA\Parameter(
     *         name="cid_id",
     *         in="path",
     *         required=true,
     *         description="ID da Cidade",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Cidade Encontrada",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Cidade não encontrada"
     *      ),
     *     security={{"bearerAuth":{}}}
     *  )
     */
    public function show(string $cid_id)
    {
        $cidade = Cidade::where('cid_id', $cid_id)->first();

        if (!$cidade) {
            return response('Não encontrado', 404)->json();
        }
        return response()->json(['message' => 'Cidade encontrada', 'cidade' => $cidade]);
    }

    /**
     * @OA\PUT(
     *     path="/api/update-cidade/{cid_id}",
     *     summary="Atualizar ou criar uma Cidade",
     *     description="Endpoint para atualizar uma nova cidade",
     *     tags={"Cidade"},
     *     @OA\Parameter(
     *         name="cid_id",
     *         in="path",
     *         required=true,
     *         description="ID da Cidade",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="cid_id", type="string", example="M"),
     *             @OA\Property(property="cid_nome", type="string", example="Maria Silva"),
     *             @OA\Property(property="cid_uf", type="string", example="José Silva")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cidade atualizada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cidade atualizada com sucesso"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="cid_nome", type="string", example="Cuiabá"),
     *                 @OA\Property(property="cid_uf", type="string", example="MT")
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
    public function update(Request $request, string $cid_id)
    {

        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        $valited = $request->validate([
            'cid_nome' => 'string',
            'cid_uf' => 'string',
        ]);

        $cidade = Cidade::where('cid_id', $cid_id)->first();
        if (!$cidade) {
            return response('Error', 404)->json(['message' => 'Usuário não encontrado']);
        } else {
            $cidade->update($valited);
        }

        return response()->json(['message' => 'Cidade atualizada', 'cidade' => $cidade]);
    }

    /**
     *  @OA\DELETE(
     *      path="/api/delete-cidade/{cid_id}",
     *      summary="Deleta Cidade",
     *      description="Remove Cidade",
     *      tags={"Cidade"},
     *     @OA\Parameter(
     *         name="cid_id",
     *         in="path",
     *         required=true,
     *         description="ID da Cidade",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Cidade Removida",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Cidade não encontrada"
     *      ),
     *     security={{"bearerAuth":{}}}
     *  )
     */
    public function destroy(string $cid_id)
    {
        $cidade = Cidade::where('cid_id', $cid_id)->first();
        if (!$cidade) {
            return response('Error', 404)->json(['message' => 'Usuário não encontrado']);
        } else {
            $cidade->delete();
        }
        return response()->json(['message' => 'Cidade Removida', 'cidade' => $cidade]);
    }
}
