<?php

namespace App\Http\Controllers\Mto;

use App\Carpeta;
use App\Filedoc;
use App\Documento;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumento;

class DocumentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->wantsJson())
            return [
                'documentos'=> Documento::with('carpeta')->whereYear('fecha',date('Y'))
                            ->orderBy('fecha','desc')
                            ->get(),
                'carpetas'  =>  Carpeta::selCarpetas(),
            ];

    }

    public function filtrar(Request $request)
    {

        $fecha_d = $request->input('fecha_d');
        $fecha_h = $request->input('fecha_h');
        $carpeta_id = $request->input('carpeta_id');

        $data[] = [
            'fecha', '>=', $fecha_d,
        ];
        $data[] = [
            'fecha', '<=', $fecha_h,
        ];
        if ($carpeta_id <> '')
            $data[] = [
                'carpeta_id', '=', $carpeta_id,
            ];

        if (request()->wantsJson())
            return Documento::with('carpeta')->where($data)
                            ->orderBy('fecha','desc')
                            ->get();

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
    public function edit(Documento $documento)
    {
        if (request()->wantsJson())
            return [
                'documento' => $documento,
                'carpetas'=> Carpeta::selCarpetas(),
                'files' => Filedoc::FilesDocumento($documento->id)->get()
            ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDocumento $request, Documento $documento)
    {
       // $this->authorize('update', $documento);

        $data = $request->validated();

        $data['empresa_id'] =  session()->get('empresa_id');
        $data['username'] = $request->user()->username;

        $documento->update($data);

        if (request()->wantsJson())
            return ['documento'=>$documento, 'message' => 'EL documento ha sido modficado'];
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
}
