<?php

namespace App\Http\Controllers\Mto;

use App\Fpago;
use App\Albacab;
use App\Archivo;
use App\Cliente;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientes;
use Spatie\Permission\Models\Permission;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Cliente::all();


        // dd(Cliente::get()->toSql());

        //\Log::info(Cliente::all()->toSql());

        if (request()->wantsJson())
            return $data;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (request()->wantsJson())
            return [
                'archivos'=> Archivo::all(),
                'fpagos'=> Fpago::all(),
            ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientes $request)
    {
        $data = $request->validated();

        //\Log::info('enviando mensaje...'.session()->get('empresa'));
        $data['empresa_id'] =  session()->get('empresa')->id;

        $data['username'] = $request->user()->username;


        $reg = Cliente::create($data);

        if (request()->wantsJson())
            return ['cliente'=>$reg, 'message' => 'EL registro ha sido creado'];
    }

    /**
     * Muestra albaranes del cliente
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function albaranes($id)
    {

       // $o = new Albacab;

        //return $o->AlbaranesCliente($id)->get();
        //return Albacab::AlbaranesCliente($id)->get();

        if (request()->wantsJson())
            return [
                'albaranes'=> Albacab::AlbaranesCliente($id)->get()
            ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        $this->authorize('update', $cliente);

        if (request()->wantsJson())
            return [
                'cliente' =>$cliente,
                'archivos'=> Archivo::all(),
                'fpagos'=> Fpago::all(),
            ];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreClientes $request, Cliente $cliente)
    {
        $this->authorize('update', $cliente);

        $data = $request->validated();

        $data['username'] = $request->user()->username;

        $cliente->update($data);

        if (request()->wantsJson())
            return ['cliente'=>$cliente, 'message' => 'EL cliente ha sido modficado'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {

        $this->authorize('delete', $cliente);

        $cliente->delete();


        if (request()->wantsJson()){
            return response()->json(Cliente::all());
        }
    }
}
