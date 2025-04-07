<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use Illuminate\Http\Request;

class EnderecoController extends Controller
{
    /**
     *  @OA\GET(
     *      path="/api/endereco",
     *      summary="GET Endereços",
     *      description="GET Paginado",
     *      tags={"Endereço"},
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
        $endereco = Endereco::paginate(15);
        if(!$endereco)
        {
            return response('Não encontrado', 404)->json();
        }
        return response()->json(['message' => 'Endereços encontrados', 'endereco' => $endereco]);
    }

    /**
     * @OA\Post(
     *     path="/api/store-endereco",
     *     summary="Cria um novo endereço",
     *     description="Endpoint para cadasatrar um novo endereço no sistema.",
     *     tags={"Endereço"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"end_tipo_logradouro","end_logradouro","end_numero","end_bairro","cid_id"},
     *             @OA\Property(property="end_tipo_logradouro", type="string", example="rua ficticia"),
     *             @OA\Property(property="end_logradouro", type="string", example="logradouro"),
     *             @OA\Property(property="end_numero", type="string", example="01"),
     *             @OA\Property(property="end_bairro", type="string", example="CPA II"),
     *             @OA\Property(property="cid_id", type="string", example="1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Endereço Cadastrado com sucesso!",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Endereço Cadastrado com sucesso!"),
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
            'end_tipo_logradouro' => 'required|string',
            'end_logradouro' => 'required|string',
            'end_numero' => 'required|string',
            'end_bairro' => 'required|string',
            'cid_id' => 'required|integer',
        ]);

        $endereco = Endereco::where('end_id', $request->end_id)->first();
        if (!$endereco) {
            $endereco = Endereco::create($valited);
        }

        return response()->json(['message' => 'Endereço cadastrado', 'endereco' => $endereco]);
    }

    /**
     *  @OA\GET(
     *      path="/api/show-endereco/{end_id}",
     *      summary="Show Endereço",
     *      description="Show Endereço",
     *      tags={"Endereço"},
     *     @OA\Parameter(
     *         name="end_id",
     *         in="path",
     *         required=true,
     *         description="ID do Endereço",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Endereço Encontrado",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Endereço não encontrado"
     *      ),
     *     security={{"bearerAuth":{}}}
     *  )
     */
    public function show(string $end_id)
    {
        $endereco = Endereco::where('end_id', $end_id)->first();

        if (!$endereco) {
            return response('Não encontrado', 404)->json();
        }
        return response()->json(['message' => 'Endereço encontrado', 'endereco' => $endereco]);
    }

    /**
     * @OA\PUT(
     *     path="/api/update-endereco/{end_id}",
     *     summary="Atualizar uma Endereco",
     *     description="Endpoint para atualizar um novo endereço",
     *     tags={"Endereço"},
     *     @OA\Parameter(
     *         name="end_id",
     *         in="path",
     *         required=true,
     *         description="ID do Endereço",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="end_tipo_logradouro", type="string", example="Rua"),
     *             @OA\Property(property="end_logradouro", type="string", example="Rua 15"),
     *             @OA\Property(property="end_numero", type="string", example="01"),
     *             @OA\Property(property="end_bairro", type="string", example="CPA III"),
     *             @OA\Property(property="cid_id", type="string", example="1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Endereço atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Endereço atualizado com sucesso"),
     *             @OA\Property(property="data", type="object",
     *             @OA\Property(property="end_tipo_logradouro", type="string", example="rua ficticia"),
     *             @OA\Property(property="end_logradouro", type="string", example="logradouro"),
     *             @OA\Property(property="end_numero", type="string", example="01"),
     *             @OA\Property(property="end_bairro", type="string", example="CPA III"),
     *             @OA\Property(property="cid_id", type="string", example="1")
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
    public function update(Request $request, string $end_id)
    {

        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        $valited = $request->validate([
            'end_tipo_logradouro' => 'string',
            'end_logradouro' => 'string',
            'end_numero' => 'string',
            'end_bairro' => 'string',
            'cid_id' => 'integer',
        ]);

        $endereco = Endereco::where('end_id', $end_id)->first();
        if (!$endereco) {
            return response('Error', 404)->json(['message' => 'Endereço não encontrado']);
        } else {
            $endereco->update($valited);
        }

        return response()->json(['message' => 'Endereço atualizado', 'endereco' => $endereco]);
    }

    /**
     *  @OA\DELETE(
     *      path="/api/delete-endereco/{end_id}",
     *      summary="Deleta Endereço",
     *      description="Remove Endereço",
     *      tags={"Endereço"},
     *     @OA\Parameter(
     *         name="end_id",
     *         in="path",
     *         required=true,
     *         description="ID do Endereço",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Endereço Removido",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Endereço não encontrado"
     *      ),
     *     security={{"bearerAuth":{}}}
     *  )
     */
    public function destroy(string $end_id)
    {
        $endereco = Endereco::where('end_id', $end_id)->first();
        if (!$endereco) {
            return response('Error', 404)->json(['message' => 'Endereço não encontrado']);
        } else {
            $endereco->delete();
        }
        return response()->json(['message' => 'Endereço Removido', 'endereco' => $endereco]);
    }
}
