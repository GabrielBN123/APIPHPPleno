<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pessoa;
use App\Http\Requests\PessoaRequest;
use OpenApi\Annotations as OA;

class PessoaController extends Controller
{
    /**
     *  @OA\GET(
     *      path="/api/pessoa",
     *      summary="GET pessoas",
     *      description="GET Paginado",
     *      tags={"Pessoa"},
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
        $pessoas = Pessoa::paginate(15);
        if (!$pessoas) {
            return response('Não encontrado', 404)->json();
        }
        return response()->json($pessoas);
    }

    /**
     * @OA\Post(
     *     path="/api/store-pessoa",
     *     summary="Cria uma nova pessoa",
     *     description="Endpoint para criar uma nova pessoa no sistema.",
     *     tags={"Pessoa"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pes_nome", "pes_data_nascimento", "pes_sexo"},
     *             @OA\Property(property="pes_nome", type="string", example="João Silva"),
     *             @OA\Property(property="pes_data_nascimento", type="string", format="date", example="2000-05-15"),
     *             @OA\Property(property="pes_sexo", type="string", example="M"),
     *             @OA\Property(property="pes_mae", type="string", example="Maria Silva"),
     *             @OA\Property(property="pes_pai", type="string", example="José Silva")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pessoa criada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Pessoa criada com sucesso"),
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
            'pes_nome' => 'required|string',
            'pes_data_nascimento' => 'required|date',
            'pes_sexo' => 'required|string',
            'pes_mae' => 'string',
            'pes_pai' => 'string',
        ]);

        $pessoa = Pessoa::where('pes_id', $request->pes_id)->first();
        if (!$pessoa) {
            $pessoa = Pessoa::create($valited);
        }

        return response()->json(['message' => 'Pessoa cadastrada', 'pessoa' => $pessoa]);
    }

    /**
     *  @OA\GET(
     *      path="/api/show-pessoa/{pes_id}",
     *      summary="Show Pessoa",
     *      description="Show Pessoa",
     *      tags={"Pessoa"},
     *     @OA\Parameter(
     *         name="pes_id",
     *         in="path",
     *         required=true,
     *         description="ID da pessoa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Pessoa Encontrada",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Pessoa não encontrada"
     *      ),
     *     security={{"bearerAuth":{}}}
     *  )
     */
    public function show(string $pes_id)
    {
        $pessoa = Pessoa::where('pes_id', $pes_id)->first();

        if (!$pessoa) {
            return response('Não encontrado', 404)->json();
        }
        return response()->json(['message' => 'Pessoa encontrada', 'pessoa' => $pessoa]);
    }

    /**
     * @OA\PUT(
     *     path="/api/update-pessoa/{pes_id}",
     *     summary="Atualizar ou criar uma pessoa",
     *     description="Endpoint para atualizar ou criar uma nova pessoa",
     *     tags={"Pessoa"},
     *     @OA\Parameter(
     *         name="pes_id",
     *         in="path",
     *         required=true,
     *         description="ID da pessoa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="pes_nome", type="string", example="João Silva"),
     *             @OA\Property(property="pes_data_nascimento", type="string", format="date", example="2000-05-15"),
     *             @OA\Property(property="pes_sexo", type="string", example="M"),
     *             @OA\Property(property="pes_mae", type="string", example="Maria Silva"),
     *             @OA\Property(property="pes_pai", type="string", example="José Silva")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pessoa atualizada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Pessoa atualizada com sucesso"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="pes_id", type="integer", example=1),
     *                 @OA\Property(property="pes_nome", type="string", example="João Silva Santos"),
     *                 @OA\Property(property="pes_data_nascimento", type="string", format="date", example="2000-05-15"),
     *                 @OA\Property(property="pes_sexo", type="string", example="M"),
     *                 @OA\Property(property="pes_mae", type="string", example="Maria Silva"),
     *                 @OA\Property(property="pes_pai", type="string", example="José Silva")
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

        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        $valited = $request->validate([
            'pes_nome' => 'string',
            'pes_data_nascimento' => 'date',
            'pes_sexo' => 'string',
            'pes_mae' => 'string',
            'pes_pai' => 'string',
        ]);

        $pessoa = Pessoa::where('pes_id', $pes_id)->first();
        if (!$pessoa) {
            return response('Error', 404)->json(['message' => 'Usuário não encontrado']);
        } else {
            $pessoa->update($valited);
        }

        return response()->json(['message' => 'Pessoa atualizada', 'pessoa' => $pessoa]);
    }

    /**
     *  @OA\DELETE(
     *      path="/api/delete-pessoa/{pes_id}",
     *      summary="Deleta Pessoa",
     *      description="Remove Pessoa",
     *      tags={"Pessoa"},
     *     @OA\Parameter(
     *         name="pes_id",
     *         in="path",
     *         required=true,
     *         description="ID da pessoa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Pessoa Removida",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Pessoa não encontrada"
     *      ),
     *     security={{"bearerAuth":{}}}
     *  )
     */
    public function destroy(string $pes_id)
    {
        $pessoa = Pessoa::where('pes_id', $pes_id)->first();
        if (!$pessoa) {
            return response('Error', 404)->json(['message' => 'Usuário não encontrado']);
        } else {
            $pessoa->delete();
        }
        return response()->json(['message' => 'Pessoa Removida', 'pessoa' => $pessoa]);
    }
}
