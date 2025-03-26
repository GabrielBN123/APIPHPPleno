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
    *
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
     * Display a listing of the resource.
     */
    /**
    *  @OA\GET(
    *      path="/api/store-cidade",
    *      summary="GET pessoas",
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
    *
    *  )
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

        $cidade = Cidade::where('pes_id', $request->pes_id)->first();
        if (!$cidade) {
            $cidade = Cidade::create($valited);
        }

        return response()->json(['message' => 'Pessoa cadastrada','cidade' => $cidade]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
