<?php

namespace App\Http\Controllers;

use App\Models\ServidorEfetivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServidorEfetivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servidor = ServidorEfetivo::paginate(15);
        return response()->json([
            'message' => 'Servidores encontrados',
            'servidor' => $servidor,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pes_id' => 'required',
            'se_matricula' => 'required'
        ]);

        try {
            DB::transaction(function () use ($validated, $request){
                $servidor = ServidorEfetivo::where('pes_id', $request->pes_id)->first();
                if(!$servidor){
                    $servidor = ServidorEfetivo::create($validated);
                }

                return response()->json([
                    'message' => 'Servidor Efetivo cadastrado!',
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $servidor = ServidorEfetivo::with('pessoa')->where('pes_id', $id)->first();
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validated = $request->validate([
            'se_matricula' => 'required'
        ]);


        try {
            DB::transaction(function () use ($validated, $id){
                $servidor = ServidorEfetivo::with('pessoa')->where('pes_id', $id)->first();
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::transaction(function () use ($id){
                $servidor = ServidorEfetivo::with('pessoa')->where('pes_id', $id)->first();
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
