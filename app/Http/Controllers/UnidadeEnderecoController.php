<?php

namespace App\Http\Controllers;

use App\Models\UnidadeEndereco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnidadeEnderecoController extends Controller
{
    /**
     *  @OA\GET(
     *      path="/api/unidade-endereco",
     *      summary="GET Unidade",
     *      description="GET Paginado",
     *      tags={"Unidade-Endereco"},
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
        $unidade = UnidadeEndereco::with(['unidade','endereco'])->paginate(15);
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
     *     path="/api/store-unidade-endereco",
     *     summary="Víncula um endereço a uma unidade",
     *     description="Endpoint para vincular um endereço a uma unidade",
     *     tags={"Unidade-Endereco"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"unid_id","end_id"},
     *             @OA\Property(property="unid_id", type="integer", example="1"),
     *             @OA\Property(property="end_id", type="integer", example="1"),
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
            'unid_id' => 'required',
            'end_id' => 'required',
        ]);

        try {
            return DB::transaction(function () use ($validated, $request){
                $unidade = UnidadeEndereco::where('unid_id', $request->unid_id)->first();
                if(!$unidade){
                    $unidade = UnidadeEndereco::create($validated);
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
     *      path="/api/show-unidade-endereco/{unid_id}",
     *      summary="Show Unidade",
     *      description="Show Unidade",
     *      tags={"Unidade-Endereco"},
     *     @OA\Parameter(
     *         name="unid_id",
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
    public function show(string $unid_id)
    {
        $unidade = UnidadeEndereco::with(['unidade','endereco'])->where('unid_id', $unid_id)->first();
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
     *     path="/api/update-unidade-endereco/{unid_id}",
     *     summary="Atualizar uma unidade",
     *     description="Endpoint para atualizar unidade",
     *     tags={"Unidade-Endereco"},
     *     @OA\Parameter(
     *         name="unid_id",
     *         in="path",
     *         required=true,
     *         description="ID da Unidade",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="end_id", type="integer", example="1"),
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
    public function update(Request $request, string $unid_id)
    {

        $validated = $request->validate([
            'end_id' => 'required',
        ]);

        try {
            return DB::transaction(function () use ($validated, $unid_id){
                $unidade = UnidadeEndereco::where('unid_id', $unid_id)->first();
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
     *      path="/api/delete-unidade-endereco/{unid_id}",
     *      summary="Deleta vinculo de unidade endereco",
     *      description="Remove vinculo de unidade endereco",
     *      tags={"Unidade-Endereco"},
     *     @OA\Parameter(
     *         name="unid_id",
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
    public function destroy(string $unid_id)
    {
        try {
            return DB::transaction(function () use ($unid_id){
                $unidade = UnidadeEndereco::where('unid_id', $unid_id)->first();
                if(!$unidade){
                    return response('Não encontrado', 404)->json([
                        'message' => 'Unidade Não encontrada!',
                    ]);
                }
                $unidade->delete();

                return response()->json(['message' => 'Vínculo Removido!',]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
