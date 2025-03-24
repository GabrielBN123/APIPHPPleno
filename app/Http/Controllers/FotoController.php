<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FotoController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|', // Aceita múltiplas imagens de até 2MB
        ]);

        $uploadedFiles = [];

        try {
            $path = Storage::disk('s3')->put('fotos', $request->file('foto'));
            $uploadedFiles = [
                'nome' => $request->file('foto')->getClientOriginalName(),
                'path' => $path,
            ];

            return response()->json([
                'message' => 'Fotos enviadas com sucesso!',
                'arquivos' => $uploadedFiles
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro!',
                'arquivos' => $$e->getMessage(),
            ], 500);

        }
    }

    /**
     * Gera um link temporário para acessar a imagem.
     */
    public function obterLinkTemporario($path)
    {
        $url = Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(5));

        return response()->json([
            'message' => 'Link temporário gerado com sucesso!',
            'url' => $url,
        ]);
    }
}
