<?php

namespace App\Http\Controllers\Admin;

use App\Carpeta;
use App\Subcarpeta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubcarpetasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Subcarpeta::with('carpeta')->get();
        //$data = Subcarpeta::all();

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
                'carpetas'  =>  Carpeta::selCarpetas(),
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
            'carpeta_id' => ['required','integer'],
            'nombre' => ['required', 'string', 'max:50'],
            'color' => ['required']
        ]);

        $data['empresa_id'] =  session()->get('empresa')->id;

        $reg = Subcarpeta::create($data);

        if (request()->wantsJson())
            return ['subcarpeta'=>$reg, 'message' => 'EL registro ha sido creado'];
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
    public function edit(Subcarpeta $subcarpeta)
    {
        if (request()->wantsJson())
            return [
                'carpetas' => Carpeta::selCarpetas(),
                'subcarpeta' =>$subcarpeta
            ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subcarpeta $subcarpeta)
    {
        $data = $request->validate([
            'carpeta_id' => ['required'],
            'nombre' => ['required', 'string', 'max:50'],
            'color' => ['required']
        ]);


        $subcarpeta->update($data);

        if (request()->wantsJson())
            return ['subcarpeta'=>$subcarpeta, 'message' => 'EL registro ha sido modficado'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subcarpeta $subcarpeta)
    {
        $subcarpeta->delete();

        if (request()->wantsJson()){
            return response()->json(Subcarpeta::with('carpeta')->get());
        }
    }
}
