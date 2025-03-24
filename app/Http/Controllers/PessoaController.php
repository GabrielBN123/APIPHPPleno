<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pessoa;
use App\Http\Requests\PessoaRequest;


class PessoaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pessoas = Pessoa::paginate(15);
        return response()->json($pessoas);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        $valited = $request->validate([
            'pes_id' => 'required|integer',
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

        return response()->json(['message' => 'Pessoa cadastrada','pessoa' => $pessoa]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pessoa = Pessoa::where('pes_id', $id)->first();

        if (!$pessoa) {
            return response('Não encontrado', 404)->json();
        }
        return response()->json(['message' => 'Pessoa encontrada','pessoa' => $pessoa]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
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
        }else{
            $pessoa->update($valited);
        }

        return response()->json(['message' => 'Pessoa atualizada','pessoa' => $pessoa]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
