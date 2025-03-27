<?php

namespace App\Http\Controllers;

use App\Models\PessoaEndereco;
use Illuminate\Http\Request;

class PessoaEnderecoController extends Controller
{
    /**
     *  @OA\GET(
     *      path="/api/pessoa-endereco",
     *      summary="GET Pessoa Endereço",
     *      description="GET Paginado",
     *      tags={"Pessoa-Endereco"},
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
        $pessoaEndereco = PessoaEndereco::with(['pessoa', 'endereco'])->paginate(15);
        if(!$pessoaEndereco)
        {
            return response('Não encontrado', 404)->json();
        }
        return response()->json(['message' => 'Pessoas Endereços encontrados', 'pessoaEndereco' => $pessoaEndereco]);
    }

    /**
     * @OA\Post(
     *     path="/api/store-pessoa-endereco",
     *     summary="Vincula Endereço a uma pessoa",
     *     description="Endpoint para vincular no sistema uma pessoa a um endereço",
     *     tags={"Pessoa-Endereco"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pes_id","end_id"},
     *             @OA\Property(property="pes_id", type="integer", example="1"),
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
        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        $valited = $request->validate([
            'pes_id' => 'required|integer',
            'end_id' => 'required|integer',
        ]);

        $pessoaEndereco = PessoaEndereco::where('pes_id', $request->pes_id)->first();
        if (!$pessoaEndereco) {
            $pessoaEndereco = PessoaEndereco::create($valited);
        }

        return response()->json(['message' => 'Vinculo cadastrado com sucesso', 'pessoaEndereco' => $pessoaEndereco]);
    }

    /**
     *  @OA\GET(
     *      path="/api/show-pessoa-endereco/{pes_id}",
     *      summary="Show Pessoa Endereço",
     *      description="Show Pessoa Endereço",
     *      tags={"Pessoa-Endereco"},
     *     @OA\Parameter(
     *         name="pes_id",
     *         in="path",
     *         required=true,
     *         description="ID da Pessoa ou ID do Endereço",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Endereço vinculado a pessoa encontrado",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Nenhum endereco vinculado a esta pessoa"
     *      ),
     *     security={{"bearerAuth":{}}}
     *  )
     */
    public function show(string $pes_id)
    {
        $pessoaEndereco = PessoaEndereco::with(['pessoa', 'endereco'])->where('pes_id', $pes_id)->orWhere('end_id', $pes_id)->first();

        if (!$pessoaEndereco) {
            return response('Não encontrado', 404)->json();
        }
        return response()->json(['message' => 'Pessoa Endereço encontrada', 'pessoaEndereco' => $pessoaEndereco]);
    }

    /**
     * @OA\PUT(
     *     path="/api/update-pessoa-endereco/{pes_id}",
     *     summary="Atualizar um endereço vinculado",
     *     description="Endpoint para atualizar um vínculo de endereço e pessoa",
     *     tags={"Pessoa-Endereco"},
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
     *             @OA\Property(property="end_id", type="integer", example="1"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pessoa Endereço atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Pessoa Endereço atualizado com sucesso"),
     *             @OA\Property(property="data", type="object",
     *             @OA\Property(property="end_id", type="integer", example="1"),
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
            'end_id' => 'integer',
        ]);

        $pessoaEndereco = PessoaEndereco::where('pes_id', $pes_id)->first();
        if (!$pessoaEndereco) {
            return response('Error', 404)->json(['message' => 'Pessoa Endereço não encontrada']);
        } else {
            $pessoaEndereco->update($valited);
        }

        return response()->json(['message' => 'Pessoa Endereço atualizada', 'pessoaEndereco' => $pessoaEndereco]);
    }

    /**
     *  @OA\DELETE(
     *      path="/api/delete-pessoa-endereco/{pes_id}",
     *      summary="Deleta Pessoa Endereço",
     *      description="Remove Pessoa Endereço",
     *      tags={"Pessoa-Endereco"},
     *     @OA\Parameter(
     *         name="pes_id",
     *         in="path",
     *         required=true,
     *         description="ID da Pessoa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Pessoa Endereço Removido",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Pessoa Endereço não encontrado"
     *      ),
     *     security={{"bearerAuth":{}}}
     *  )
     */
    public function destroy(string $pes_id)
    {
        $pessoaEndereco = PessoaEndereco::where('pes_id', $pes_id)->first();
        if (!$pessoaEndereco) {
            return response('Error', 404)->json(['message' => 'Pessoa Endereço não encontrada']);
        } else {
            $pessoaEndereco->delete();
        }
        return response()->json(['message' => 'Pessoa Endereço Removida', 'pessoaEndereco' => $pessoaEndereco]);
    }
}
