<?php

namespace App\Http\Controllers;

use App\Models\FotoPessoa;
use App\Models\Pessoa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FotoController extends Controller
{

    /**
     *  @OA\GET(
     *      path="/api/foto-pessoa",
     *      summary="GET Foto Pessoa",
     *      description="GET Paginado",
     *      tags={"FotoPessoa"},
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
        $fotoPessoa = FotoPessoa::with('pessoa')->paginate(15);
        if(!$fotoPessoa)
        {
            return response('Não encontrado', 404)->json();
        }
        return response()->json(['message' => 'Fotos encontradas', 'fotoPessoa' => $fotoPessoa]);
    }

    /**
     * @OA\Post(
     *     path="/api/store-foto-pessoa/{pes_id}",
     *     summary="Armazenar foto vínculada a pessoa",
     *     description="Endpoint enviar foto de usuário, vinculando ao mesmo.",
     *     tags={"FotoPessoa"},
     *     @OA\Parameter(
     *         name="pes_id",
     *         in="path",
     *         required=true,
     *         description="ID da pessoa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="foto",
     *                     description="Arquivo de foto (imagem)",
     *                     type="string",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Foto enviada com sucesso!",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Foto enviada com sucesso!"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="documento_url", type="string", example="https://seuservidor.com/uploads/documento.pdf")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro na requisição"
     *     ),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function store(Request $request, string $pes_id)
    {
        $request->validate([
            'foto' => 'required|image|',
        ]);

        $pessoa = Pessoa::where('pes_id', $pes_id)->first();

        if (!$pessoa) {
            return response('Não encontrado', 404)->json();
        }


        try {
            $path = $request->file('foto')->store('fotos/uploads', 's3');

            $foto = FotoPessoa::where('pes_id', $pes_id)->first();

            if(!$foto){
                $foto = FotoPessoa::create([
                    'pes_id' => $pes_id,
                    'fp_data' => Carbon::now(),
                    'fp_bucket' => env('AWS_BUCKET'),
                    'fp_hash' => $path,
                ]);
            }else{
                $foto->update([
                    'fp_data' => Carbon::now(),
                    'fp_bucket' => env('AWS_BUCKET'),
                    'fp_hash' => $path,
                ]);

            }

            return response()->json([
                'message' => 'Foto pessoa Cadastrada!',
                'Foto' => $foto,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro!',
                'arquivos' => $e->getMessage(),
            ], 500);

        }
    }

    /**
     * @OA\PUT(
     *     path="/api/update-foto-pessoa/{pes_id}",
     *     summary="Atualizar foto de usuário",
     *     description="Endpoint atualizar foto de usuário, vinculando ao mesmo.",
     *     tags={"FotoPessoa"},
     *     @OA\Parameter(
     *         name="pes_id",
     *         in="path",
     *         required=true,
     *         description="ID da pessoa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="foto",
     *                     description="Arquivo de foto (imagem)",
     *                     type="string",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Foto enviada com sucesso!",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Foto enviada com sucesso!"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="documento_url", type="string", example="https://seuservidor.com/uploads/documento.pdf")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro na requisição"
     *     ),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function update(Request $request, string $pes_id)
    {
        $request->validate([
            'foto' => 'required|image|',
        ]);

        $pessoa = Pessoa::where('pes_id', $pes_id)->first();

        if (!$pessoa) {
            return response('Não encontrado', 404)->json();
        }


        try {

            $foto = FotoPessoa::where('pes_id', $pes_id)->first();

            if(!$foto){
                return response('Foto Pessoa não encontrado', 404)->json();
            }

            $path = $request->file('foto')->store('fotos/uploads', 's3');

            $foto = FotoPessoa::update([
                'fp_id' => $pes_id,
                'pes_id' => $pes_id,
                'fp_data' => Carbon::now(),
                'fp_bucket' => env('AWS_BUCKET'),
                'fp_hash' => $path,
            ]);

            return response()->json([
                'message' => 'Foto pessoa Atualizada!',
                'Foto' => $foto,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro!',
                'arquivos' => $e->getMessage(),
            ], 500);

        }
    }

    /**
     * @OA\GET(
     *     path="/api/show-foto-pessoa/{pes_id}",
     *     summary="Trazer foto do usuário com link temporário",
     *     description="Endpoint retornar link temporário da foto do usuário",
     *     tags={"FotoPessoa"},
     *     @OA\Parameter(
     *         name="pes_id",
     *         in="path",
     *         required=true,
     *         description="ID da pessoa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Foto encontrada com sucesso!",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro na requisição"
     *     ),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function show(string $pes_id)
    {

        $foto = FotoPessoa::where('pes_id', $pes_id)->first();

        if (!$foto) {
            return response('Não encontrado', 404)->json();
        }

        if (!Storage::disk('s3')->exists($foto->fp_hash)) {
            return response()->json(['error' => 'Arquivo não encontrado no MinIO'], 404);
        }

        $url = Storage::disk('s3')->temporaryUrl(
            $foto->fp_hash,
            now()->addMinutes(5)
        );

        $url = str_replace(env('AWS_ENDPOINT'), env('AWS_PUBLIC_URL'), $url);
        // $url = str_replace('http://minio:9000', 'http://localhost:9003/', $url);
        return response()->json([
            'message' => 'Link temporário gerado com sucesso!',
            'url' => $url,
        ]);
    }

    /**
     *  @OA\DELETE(
     *      path="/api/delete-foto-pessoa/{pes_id}",
     *      summary="Deleta Foto Pessoa",
     *      description="Remove Foto Pessoa",
     *      tags={"FotoPessoa"},
     *     @OA\Parameter(
     *         name="pes_id",
     *         in="path",
     *         required=true,
     *         description="ID da Pessoa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Foto Pessoa Removida",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Foto Pessoa não encontrada"
     *      ),
     *     security={{"bearerAuth":{}}}
     *  )
     */
    public function destroy(string $pes_id)
    {
        $fotoPessoa = FotoPessoa::where('pes_id', $pes_id)->first();
        if (!$fotoPessoa) {
            return response('Error', 404)->json(['message' => 'Foto pessoa não encontrada']);
        } else {
            $fotoPessoa->delete();
        }
        return response()->json(['message' => 'Foto pessoa Removida', 'fotoPessoa' => $fotoPessoa]);
    }


}
