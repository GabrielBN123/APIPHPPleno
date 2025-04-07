<?php

namespace App\Http\Controllers;

use App\Models\Unidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnidadeController extends Controller
{
    /**
     *  @OA\GET(
     *      path="/api/unidade",
     *      summary="GET Unidade",
     *      description="GET Paginado",
     *      tags={"Unidade"},
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
        $unidade = Unidade::with('endereco.endereco')->paginate(15);
        if (!$unidade) {
            return response('Não encontrado', 404)->json();
        }
        return response()->json([
            'message' => 'Unidades encontradas',
            'unidade' => $unidade,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/store-unidade",
     *     summary="Cria uma unidade no sistema",
     *     description="Endpoint para cadastrar uma unidade",
     *     tags={"Unidade"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"st_data_admissao", "st_data_demissao"},
     *             @OA\Property(property="unid_nome", type="string", example="Unidade do CPA II"),
     *             @OA\Property(property="unid_sigla", type="string", example="GTCPAII"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vínculo realizado com sucesso!",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vínculo realizado com sucesso!"),
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
        $validated = $request->validate([
            'unid_nome' => 'required|string',
            'unid_sigla' => 'required|string',
        ]);

        try {
            return DB::transaction(function () use ($validated, $request){
                $unidade = Unidade::where('unid_nome', $request->unid_nome)->first();
                if(!$unidade){
                    $unidade = Unidade::create($validated);
                }

                return response()->json([
                    'message' => 'Unidade Cadastrada!',
                    'unidade' => $unidade,
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     *  @OA\GET(
     *      path="/api/show-unidade/{unidade_id}",
     *      summary="Show Unidade",
     *      description="Show Unidade",
     *      tags={"Unidade"},
     *     @OA\Parameter(
     *         name="unidade_id",
     *         in="path",
     *         required=true,
     *         description="ID da Unidade",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Unidade encontrada",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Nenhuma unidade encontrda"
     *      ),
     *     security={{"bearerAuth":{}}}
     *  )
     */
    public function show(string $unidade_id)
    {
        $unidade = Unidade::where('unid_id', $unidade_id)->first();
        if(!$unidade){
            return response('Não encontrado', 404)->json([
                'message' => 'Unidade Não encontrada!',
            ]);
        }
        return response()->json([
            'message' => 'Unidade Encontrada!',
            'unidade' => $unidade,
        ]);

    }

    /**
     * @OA\PUT(
     *     path="/api/update-unidade/{unidade_id}",
     *     summary="Atualizar uma unidade",
     *     description="Endpoint para atualizar unidade",
     *     tags={"Unidade"},
     *     @OA\Parameter(
     *         name="unidade_id",
     *         in="path",
     *         required=true,
     *         description="ID da Unidade",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="unid_nome", type="string", example="Unidade do CPA II"),
     *             @OA\Property(property="unid_sigla", type="string",example="GTCPAII"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Unidade atualizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unidade atualizado"),
     *             @OA\Property(property="data", type="object",
     *             @OA\Property(property="unid_nome", type="string", example="Unidade do CPA II"),
     *             @OA\Property(property="unid_sigla", type="string",example="GTCPAII"),
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
    public function update(Request $request, string $unidade_id)
    {

        $validated = $request->validate([
            'unid_nome' => 'string',
            'unid_sigla' => 'string',
        ]);

        try {
            return DB::transaction(function () use ($validated, $unidade_id){
                $unidade = Unidade::where('unid_id', $unidade_id)->first();
                if(!$unidade){
                    return response('Não encontrado', 404)->json([
                        'message' => 'Unidade Não encontrada!',
                    ]);
                }
                $unidade->update($validated);

                return response()->json([
                    'message' => 'Unidade Atualizada!',
                    'unidade' => $unidade,
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     *  @OA\DELETE(
     *      path="/api/delete-unidade/{unidade_id}",
     *      summary="Deleta Unidade",
     *      description="Remove Unidade",
     *      tags={"Unidade"},
     *     @OA\Parameter(
     *         name="unidade_id",
     *         in="path",
     *         required=true,
     *         description="ID da uniade",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Unidade Removida",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Unidade não encontrada"
     *      ),
     *     security={{"bearerAuth":{}}}
     *  )
     */
    public function destroy(string $unidade_id)
    {
        try {
            return DB::transaction(function () use ($unidade_id){
                $unidade = Unidade::where('unid_id', $unidade_id)->first();
                if(!$unidade){
                    return response('Não encontrado', 404)->json([
                        'message' => 'Unidade Não encontrada!',
                    ]);
                }
                $unidade->delete();

                return response()->json(['message' => 'Unidade Removida!',]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
