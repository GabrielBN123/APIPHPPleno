<?php

namespace App\Http\Controllers;

use App\Models\ServidorTemporario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServidorTemporarioController extends Controller
{
    /**
     *  @OA\GET(
     *      path="/api/servidor-temporario",
     *      summary="GET Servidor Temporario",
     *      description="GET Paginado",
     *      tags={"Servidor-Temporario"},
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
        $servidor = ServidorTemporario::with('pes_id')->paginate(15);
        return response()->json([
            'message' => 'Servidores encontrados',
            'servidor' => $servidor,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/store-servidor-temporario",
     *     summary="Vincula torna pessoa em servidor temporario",
     *     description="Endpoint para vincular pessoa em servidor temporario",
     *     tags={"Servidor-Temporario"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pes_id","st_data_admissao", "st_data_demissao"},
     *             @OA\Property(property="pes_id", type="integer", example="1"),
     *             @OA\Property(property="st_data_admissao", type="string", format="date", example="2024-05-15"),
     *             @OA\Property(property="st_data_demissao", type="string", format="date", example="2025-05-15"),
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
            'pes_id' => 'required',
            'st_data_admissao' => 'required|date',
            'st_data_demissao' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($validated, $request){
                $servidor = ServidorTemporario::where('pes_id', $request->pes_id)->first();
                if(!$servidor){
                    $servidor = ServidorTemporario::create($validated);
                }

                return response()->json([
                    'message' => 'Servidor Temporario cadastrado!',
                    'servidor' => $servidor,
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
     *      path="/api/show-servidor-temporario/{pes_id}",
     *      summary="Show Servidor Temporario",
     *      description="Show Servidor Temporario",
     *      tags={"Servidor-Temporario"},
     *     @OA\Parameter(
     *         name="pes_id",
     *         in="path",
     *         required=true,
     *         description="ID da Pessoa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Servidor Temporario encontrado",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Nenhum vínculo temporario encontrado para esta pessoa"
     *      ),
     *     security={{"bearerAuth":{}}}
     *  )
     */
    public function show(string $pes_id)
    {
        $servidor = ServidorTemporario::with('pessoa')->where('pes_id', $pes_id)->first();
        if(!$servidor){
            return response('Não encontrado', 404)->json([
                'message' => 'Servidor Não encontrado!',
            ]);
        }
        return response()->json([
            'message' => 'Servidor encontrado!',
            'servidor' => $servidor,
        ]);

    }

    /**
     * @OA\PUT(
     *     path="/api/update-servidor-temporario/{pes_id}",
     *     summary="Atualizar um servidor temporario",
     *     description="Endpoint para atualizar servidor temporario",
     *     tags={"Servidor-Temporario"},
     *     @OA\Parameter(
     *         name="pes_id",
     *         in="path",
     *         required=true,
     *         description="ID da Pessoa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="st_data_admissao", type="string", format="date", example="2024-05-15"),
     *             @OA\Property(property="st_data_demissao", type="string", format="date", example="2025-05-15"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Servidor Temporario atualizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Servidor Temporario atualizado"),
     *             @OA\Property(property="data", type="object",
     *             @OA\Property(property="st_data_admissao", type="string", format="date", example="2024-05-15"),
     *             @OA\Property(property="st_data_demissao", type="string", format="date", example="2025-05-15"),
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
    public function update(Request $request, string $pes_id)
    {

        $validated = $request->validate([
            'st_data_admissao' => 'date',
            'st_data_demissao' => 'date',
        ]);


        try {
            DB::transaction(function () use ($validated, $pes_id){
                $servidor = ServidorTemporario::with('pessoa')->where('pes_id', $pes_id)->first();
                if(!$servidor){
                    return response('Não encontrado', 404)->json([
                        'message' => 'Servidor Não encontrado!',
                    ]);
                }
                $servidor->update($validated);

                return response()->json([
                    'message' => 'Servidor atualizado!',
                    'servidor' => $servidor,
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
     *      path="/api/delete-servidor-temporario/{pes_id}",
     *      summary="Deleta Servidor Temporario",
     *      description="Remove Servidor Temporario",
     *      tags={"Servidor-Temporario"},
     *     @OA\Parameter(
     *         name="pes_id",
     *         in="path",
     *         required=true,
     *         description="ID da Pessoa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Servidor Temporario Removido",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Servidor Temporario não encontrado"
     *      ),
     *     security={{"bearerAuth":{}}}
     *  )
     */
    public function destroy(string $pes_id)
    {
        try {
            DB::transaction(function () use ($pes_id){
                $servidor = ServidorTemporario::with('pessoa')->where('pes_id', $pes_id)->first();
                if(!$servidor){
                    return response('Não encontrado', 404)->json([
                        'message' => 'Servidor Não encontrado!',
                    ]);
                }
                $servidor->delete();

                return response()->json(['message' => 'Servidor Removido!',]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
