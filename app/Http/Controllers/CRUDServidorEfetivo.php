<?php

namespace App\Http\Controllers;

use App\Models\ServidorEfetivo;
use Illuminate\Http\Request;

class CRUDServidorEfetivo extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['response' => 'SUCESSO']);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ServidorEfetivo $servidorEfetivo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServidorEfetivo $servidorEfetivo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServidorEfetivo $servidorEfetivo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServidorEfetivo $servidorEfetivo)
    {
        //
    }
}
