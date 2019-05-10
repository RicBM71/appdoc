<?php

namespace App\Http\Controllers\Mto;

use App\Extracto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
            return Extracto::whereYear('fecha',date('Y'))
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

}
