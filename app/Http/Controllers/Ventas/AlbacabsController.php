<?php

namespace App\Http\Controllers\Ventas;

use App\Iva;
use App\Fpago;
use App\Albacab;
use App\Cliente;
use App\Retencion;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAlbaranes;

class AlbacabsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data =  Albacab::with(['cliente','albalins'])->get();
       // dd($data);

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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit(Albacab $albacab)
    {

        //return $this->clientesFacturables();

        if (request()->wantsJson())
            return [
                'albaran' =>  $albacab,
                'clientes'=>  $this->clientesFacturables(),
                'fpagos'  =>  Fpago::selectFPagos(),
                'ivas'=> Iva::all(),
                'retenciones'=> Retencion::all(),
        ];
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreAlbaranes $request, Albacab $albacab)
    {
        //$this->authorize('update', $albacab);

        
        $data = $request->validated();

        $data['empresa_id'] =  session()->get('empresa');
        $data['username'] = $request->user()->username;

       // return $albacab;

        $albacab->update($data);

        if (request()->wantsJson())
            return ['albaran'=>$albacab, 'message' => 'EL albarán ha sido modificado'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     *
     * Selecciona clientes facturables y prepara para JSON select
     *
     */

    private function clientesFacturables($nombre = null){

        $arr=[];

        $cli = Cliente::Facturables($nombre)->get();
        foreach($cli as $row){
             $arr[] = ['name' => $row->nombre, 'id' => $row->id];
        }

        return $arr;

    }

}
