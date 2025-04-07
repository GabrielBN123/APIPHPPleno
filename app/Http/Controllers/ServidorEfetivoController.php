<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use App\Models\Endereco;
use App\Models\Pessoa;
use App\Models\PessoaEndereco;
use App\Models\ServidorEfetivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServidorEfetivoController extends Controller
{
    /**
     *  @OA\GET(
     *      path="/api/servidor-efetivo",
     *      summary="GET Servidor Efetivo",
     *      description="GET Paginado",
     *      tags={"Servidor-Efetivo"},
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
        $servidor = ServidorEfetivo::with('pessoa')->paginate(15);
        if (!$servidor) {
            return response('Não encontrado', 404)->json();
        }
        return response()->json([
            'message' => 'Servidores encontrados',
            'servidor' => $servidor,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/store-servidor-efetivo",
     *     summary="Vincula torna pessoa em servidor efetivo",
     *     description="Endpoint para vincular pessoa em servidor efetivo",
     *     tags={"Servidor-Efetivo"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pes_id","se_matricula"},
     *             @OA\Property(property="pes_id", type="integer", example="1"),
     *             @OA\Property(property="se_matricula", type="string", example="205400"),
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
            'se_matricula' => 'required'
        ]);

        try {
            return DB::transaction(function () use ($validated, $request){
                $servidor = ServidorEfetivo::where('pes_id', $request->pes_id)->first();
                if(!$servidor){
                    $servidor = ServidorEfetivo::create($validated);
                }

                return response()->json([
                    'message' => 'Servidor Efetivo cadastrado!',
                    'servidor' => $servidor,
                ]);
            });
            return response()->json(['message' => 'Servidor Efetivo cadastrado!',]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     *  @OA\GET(
     *      path="/api/show-servidor-efetivo/{pes_id}",
     *      summary="Show Servidor Efetivo",
     *      description="Show Servidor Efetivo",
     *      tags={"Servidor-Efetivo"},
     *     @OA\Parameter(
     *         name="pes_id",
     *         in="path",
     *         required=true,
     *         description="ID da Pessoa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Servidor Efetivo encontrado",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Nenhum vínculo de servidor efetivo encontrado para esta pessoa"
     *      ),
     *     security={{"bearerAuth":{}}}
     *  )
     */
    public function show(string $pes_id)
    {
        $servidor = ServidorEfetivo::with('pessoa')->where('pes_id', $pes_id)->first();
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
     *     path="/api/update-servidor-efetivo/{pes_id}",
     *     summary="Atualizar um servidor efetivo",
     *     description="Endpoint para atualizar servidor efetivo",
     *     tags={"Servidor-Efetivo"},
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
     *             @OA\Property(property="se_matricula", type="string", example="205400"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Servidor Efetivo Atualizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Servidor Efetivo Atualizado"),
     *             @OA\Property(property="data", type="object",
     *             @OA\Property(property="se_matricula", type="integer", example="205400"),
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
            'se_matricula' => 'string'
        ]);


        try {
            return DB::transaction(function () use ($validated, $pes_id){
                $servidor = ServidorEfetivo::with('pessoa')->where('pes_id', $pes_id)->first();
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
            return response()->json(['message' => 'Servidor atualizado!',]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     *  @OA\DELETE(
     *      path="/api/delete-servidor-efetivo/{pes_id}",
     *      summary="Deleta Servidor Efetivo",
     *      description="Remove Servidor Efetivo",
     *      tags={"Servidor-Efetivo"},
     *     @OA\Parameter(
     *         name="pes_id",
     *         in="path",
     *         required=true,
     *         description="ID da Pessoa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Servidor Efetivo Removido",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Servidor Efetivo não encontrado"
     *      ),
     *     security={{"bearerAuth":{}}}
     *  )
     */
    public function destroy(string $pes_id)
    {
        try {
            return DB::transaction(function () use ($pes_id){
                $servidor = ServidorEfetivo::with('pessoa')->where('pes_id', $pes_id)->first();
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
