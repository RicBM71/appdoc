<?php

namespace App\Http\Controllers\Admin;

use App\Empresa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmpresas;

class EmpresasController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Empresa::all();

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

        $data = $request->validate([
            'nombre'        => ['required', 'string', 'max:50'],
            'razon'         => ['nullable','string', 'max:50'],
            'cif'           => ['nullable','string', 'max:50'],
            'poblacion'     => ['nullable','string', 'max:50'],
            'direccion'     => ['nullable','string', 'max:50'],
            'cpostal'       => ['nullable','string', 'max:50'],
            'provincia'     => ['nullable','string', 'max:50'],
            'telefono1'     => ['nullable','string', 'max:20'],
            'telefono2'     => ['nullable','string', 'max:20'],
            'contacto'      => ['nullable','string', 'max:50'],
            'email'         => ['nullable','string', 'max:50'],
            'web'           => ['nullable','string', 'max:50'],
            'txtpie1'       => ['nullable','string'],
            'txtpie2'       => ['nullable','string'],
            'flags'         => ['nullable','string', 'max:50'],
            'sigla'         => ['nullable','string', 'max:50'],
            'carpeta'       => ['nullable','string', 'max:50'],
            'titulo'        => ['nullable','string', 'max:50'],
            'logo'          => ['nullable','string', 'max:50'],
            'certificado'   => ['nullable','string', 'max:50'],
            'passwd_cer'    => ['nullable','string', 'max:50'],
            'carpeta_id'    => ['nullable','integer'],
        ]);

        $data['username'] = $request->user()->username;


        $reg = Empresa::create($data);

        if (request()->wantsJson())
            return ['empresa'=>$reg, 'message' => 'EL registro ha sido creado'];


    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Empresa $empresa )
    {
        if (request()->wantsJson())
            return [
                'empresa' =>$empresa
            ];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreEmpresas $request, Empresa $empresa)
    {
        //return $request;

        // $data = $request->validate([
        //     'nombre'        => ['required', 'string', 'max:50'],
        //     'razon'         => ['nullable','string', 'max:50'],
        //     'cif'           => ['nullable','string', 'max:50'],
        //     'poblacion'     => ['nullable','string', 'max:50'],
        //     'direccion'     => ['nullable','string', 'max:50'],
        //     'cpostal'       => ['nullable','string', 'max:50'],
        //     'provincia'     => ['nullable','string', 'max:50'],
        //     'telefono1'     => ['nullable','string', 'max:20'],
        //     'telefono2'     => ['nullable','string', 'max:20'],
        //     'contacto'      => ['nullable','string', 'max:50'],
        //     'email'         => ['nullable','string', 'max:50'],
        //     'web'           => ['nullable','string', 'max:50'],
        //     'txtpie1'       => ['nullable','string'],
        //     'txtpie2'       => ['nullable','string'],
        //     'flags'         => ['nullable','string', 'max:50'],
        //     'sigla'         => ['nullable','string', 'max:50'],
        //     'carpeta'       => ['nullable','string', 'max:50'],
        //     'titulo'        => ['nullable','string', 'max:50'],
        //     'logo'          => ['nullable','string', 'max:50'],
        //     'certificado'   => ['nullable','string', 'max:50'],
        //     'passwd_cer'    => ['nullable','string', 'max:50'],
        //     'carpeta_id'    => ['nullable','integer'],
        // ]);

        $data = $request->validated();

        $data['username'] = $request->user()->username;

        $empresa->update($data);

        if (request()->wantsJson())
            return ['empresa'=>$empresa, 'message' => 'EL registro ha sido modficado'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Empresa $empresa)
    {
        $empresa->delete();


        if (request()->wantsJson()){
            return response()->json(Empresa::all());
        }
    }
}
