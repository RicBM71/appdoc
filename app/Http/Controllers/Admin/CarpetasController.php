<?php

namespace App\Http\Controllers\Admin;

use App\Carpeta;
use App\Archivo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarpetasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Carpeta::with('archivo')->get();
        //$data = Carpeta::all();

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
                'archivos'  =>  Archivo::selArchivos(),
            ];
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
            'archivo_id' => ['required','integer'],
            'nombre' => ['required', 'string', 'max:50'],
            'color' => ['required']
        ]);

        $data['empresa_id'] =  session()->get('empresa')->id;

        $reg = Carpeta::create($data);

        if (request()->wantsJson())
            return ['carpeta'=>$reg, 'message' => 'EL registro ha sido creado'];
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
    public function edit(Carpeta $carpeta)
    {
        if (request()->wantsJson())
            return [
                'archivos' => Archivo::selArchivos(),
                'carpeta' =>$carpeta
            ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Carpeta $carpeta)
    {
        $data = $request->validate([
            'archivo_id' => ['required'],
            'nombre' => ['required', 'string', 'max:50'],
            'color' => ['required']
        ]);


        $carpeta->update($data);

        if (request()->wantsJson())
            return ['carpeta'=>$carpeta, 'message' => 'EL registro ha sido modficado'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Carpeta $carpeta)
    {
        $carpeta->delete();

        if (request()->wantsJson()){
            return response()->json(Carpeta::with('archivo')->get());
        }
    }
}
