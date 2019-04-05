<?php

namespace App\Http\Controllers\Ventas;

use App\Iva;
use App\Fpago;
use App\Albacab;
use App\Cliente;
use App\Contador;
use App\Retencion;
use App\Vencimiento;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAlbaranes;
use Spatie\Permission\Models\Permission;

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
        $this->authorize('create', new Albacab);

        if (request()->wantsJson())
            return [
                'clientes'=>  Cliente::selClientesFacturables(),
                'fpagos'  =>  Fpago::selFPagos(),
                'vencimientos'  =>  Vencimiento::selVencimientos(),
                'ivas'=> Iva::all(),
                'retenciones'=> Retencion::all(),
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAlbaranes $request)
    {

        //return $request;

        $data = $request->validated();

        $data['ejercicio']   = date('Y',strtotime($data['fecha_alb']));
        $data['empresa_id'] =  session()->get('empresa');
        $data['username'] = $request->user()->username;

        $contador = Contador::incrementaContador($data['ejercicio']);

        $data['serie']= $contador->seriealb;
        $data['albaran']= $contador->albaran;

        //return $data;
        $reg = Albacab::create($data);

        if (request()->wantsJson())
            return ['albaran'=>$reg, 'message' => 'EL registro ha sido creado'];

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
        $this->authorize('update', $albacab);

        if (request()->wantsJson())
            return [
                'albaran' =>  $albacab,
                'clientes'=>  Cliente::selClientesFacturables(),
                'fpagos'  =>  Fpago::selFPagos(),
                'vencimientos'  =>  Vencimiento::selVencimientos(),
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

        $this->authorize('update', $albacab);

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
    public function destroy(Albacab $albacab)
    {

        $this->authorize('delete', $albacab);

        $albacab->delete();

        if (request()->wantsJson()){
            return response()->json(Albacab::with(['cliente','albalins'])->get());
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function facturar(Request $request, Albacab $albacab)
    {

        $this->authorize('update', $albacab);

        $data = $request->validate([
            'factura' => 'nullable|string',
            'fecha_fac' => 'nullable|date',
        ]);

        if (is_null($data['fecha_fac'])){
            $data['fecha_fac'] = date('Y-m-d');
            $data['ejefac'] = date('Y');
        }else{
            $data['ejefac'] = date('Y',strtotime($data['fecha_fac']));
        }

        if (is_null($data['factura'])){
            $data['factura'] = Contador::incrementaContadorFactura($data['ejefac']);
        }

        $data['username'] = $request->user()->username;

        $albacab->update($data);


        if (request()->wantsJson())
            return ['albaran'=>$albacab, 'message' => 'EL albarán ha sido facturado'];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function desfacturar(Request $request, Albacab $albacab)
    {

        $this->authorize('update', $albacab);

        $data = [
            'factura'   => null,
            'fecha_fac' => null,
            'ejefac'   => 0
        ];

        Contador::restaContadorFactura( $albacab->ejefac, $albacab->factura) ?
            $msg = 'Contador sincronizado' :
            $msg = '¡Revisar contador!' ;

        $albacab->update($data);

        if (request()->wantsJson())
            return ['albaran'=>$albacab, 'message' => 'EL albarán ha sido desfacturado '.$msg];
    }

}
