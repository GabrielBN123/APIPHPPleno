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
     * @OA\Post(
     *     path="/api/fotos/upload/{pes_id}",
     *     summary="Enviar foto",
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
     *     )
     * )
     */
    public function upload(Request $request, string $pes_id)
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
                    'fp_id' => $pes_id,
                    'pes_id' => $pes_id,
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
     * @OA\GET(
     *     path="/api/get-foto/{pes_id}",
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
     *     )
     * )
     */
    public function obterLinkTemporario(string $pes_id)
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

        $url = str_replace('minio', 'localhost', $url);
        return response()->json([
            'message' => 'Link temporário gerado com sucesso!',
            'url' => $url,
        ]);
    }
}
