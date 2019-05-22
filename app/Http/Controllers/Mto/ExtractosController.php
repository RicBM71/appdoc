<?php

namespace App\Http\Controllers\Mto;

use App\Extracto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ExtractosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (request()->wantsJson())
            return Extracto::with('documentos','documentos.carpeta')->whereYear('fecha',date('Y'))
                            ->orderBy('fecha')
                            ->get();

    }

/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filtrar(Request $request)
    {

        $fecha_d = $request->input('fecha_d');
        $fecha_h = $request->input('fecha_h');
        $dh = $request->input('dh');

        $data[] = [
            'fecha', '>=', $fecha_d,
        ];
        $data[] = [
            'fecha', '<=', $fecha_h,
        ];
        if ($dh <> 'T')
            $data[] = [
                'dh', '=', $dh,
            ];

        if (request()->wantsJson())
            return Extracto::where($data)
                            ->orderBy('fecha')
                            ->get();

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

    public function importar(){

        $this->validate(request(),[
            'extracto' => 'required'
    	]);

     //    $extension = request()->file('avatar')->extension();

    	// $path = Storage::disk('public')->putFileAs(
     //        'avatars', request()->file('avatar'), $user->id.'.'.$extension
     //    );

    	//$foto = request()->file('foto');

    	//foto laravel lo convierte en un instancia de la clase uploadedfiles
    	// por lo que tenemos varios métodos, store
    	// guarda la imagen en storage/public
    			//TODO: probar con subcarpeta
    	//$fotoUrl = $foto->store('public');
    	//return Storage::url($fotoUrl);

    	//dejar así:
        $foto = request()->file('extracto')->store('extractos','public');

        //return request();



       // $foto = request()->file('extracto')->storeAs('extractos','public.txt');

        \Log::info('enviando mensaje...'.request());

    	$fotoUrl = Storage::url($foto);

        return ['url'=>$fotoUrl];

    }
}
