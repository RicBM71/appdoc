<?php

namespace App\Http\Controllers\Mto;

use App\Cliente;
use App\Transferencia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransferencia;

class TransferenciasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $transferencias = Transferencia::with(['cliente'])->get();

        if (request()->wantsJson())
            return [
                'transferencias'=> $transferencias,
            ];

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (request()->wantsJson()){
            return [
                'clientes'  =>  Cliente::selClientes()->iban()->get(),
            ];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransferencia $request)
    {
        $data = $request->validated();

        $cliente = Cliente::find($request->input('cliente_id'));

        $data['empresa_id'] =  session()->get('empresa')->id;
        $data['iban_abono'] = $cliente->iban;
        $data['bic_abono'] = $cliente->bic;

        $data['username'] = $request->user()->username;

        $reg = Transferencia::create($data);

        if (request()->wantsJson())
            return ['transferencia'=>$reg, 'message' => 'EL registro ha sido creado'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Transferencia $transferencia)
    {
        if (request()->wantsJson())
            return [
                'transferencia' => $transferencia,
                'clientes'=> Cliente::selClientes()->iban()->get(),
            ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTransferencia $request, Transferencia $transferencia)
    {
        $data = $request->validated();

        $data['username'] = $request->user()->username;

        $transferencia->update($data);

        if (request()->wantsJson())
            return ['transferencia'=>$transferencia, 'message' => 'La transferencia ha sido modficada'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transferencia $transferencia)
    {
        $transferencia->delete();
    }


}
