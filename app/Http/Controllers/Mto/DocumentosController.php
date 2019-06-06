<?php

namespace App\Http\Controllers\Mto;

use App\Archivo;
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
                'documentos'=> Documento::ordinarios()->with(['archivo','carpeta'])->whereYear('fecha',date('Y'))
                            ->orderBy('fecha','desc')
                            ->get(),
                'archivos'  =>  Archivo::selArchivos(),
            ];

    }

    public function filtrar(Request $request)
    {

        $fecha_d = $request->input('fecha_d');
        $fecha_h = $request->input('fecha_h');
        $archivo_id = $request->input('archivo_id');

        $data[] = [
            'fecha', '>=', $fecha_d,
        ];
        $data[] = [
            'fecha', '<=', $fecha_h,
        ];
        if ($archivo_id <> '')
            $data[] = [
                'archivo_id', '=', $archivo_id,
            ];

        if (request()->wantsJson())
            return [
                'documentos'=> Documento::with(['archivo','carpeta'])->where($data)
                            ->orderBy('fecha','desc')
                            ->get()
            ];

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
                'archivos'=> Archivo::selArchivos(),
                'carpetas'=> []
            ];

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDocumento $request)
    {

        $data = $request->validated();

        //\Log::info('enviando mensaje...'.session()->get('empresa'));
        $data['empresa_id'] =  session()->get('empresa')->id;

        $data['username'] = $request->user()->username;

        $reg = Documento::create($data);

        $extracto_id = $request->get('extracto_id');
        if ($extracto_id > 0)
            $reg->extractos()->sync($extracto_id);

        if (request()->wantsJson())
            return ['documento'=>$reg, 'message' => 'EL registro ha sido creado'];
    }

    public function attach(Request $request, Documento $documento){


        $documento->extractos()->attach($request->get('extracto_id'));
        return response('ok',200);
    }

    public function detach(Request $request, Documento $documento){

        $documento->extractos()->detach($request->get('extracto_id'));
        return response('ok',200);
    }


    public function extracto(Request $request){

        $data['fecha'] = $request->get('fecha');
        $data['concepto'] = $request->get('concepto');

        $data['empresa_id'] =  session()->get('empresa')->id;
        $data['username'] = $request->user()->username;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //return Carpeta::(1)->get();


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Documento $documento)
    {

        if ($documento->confidencial && !auth()->user()->hasRole('Admin')){
            abort(404);
        }

        if (request()->wantsJson())
            return [
                'documento' => $documento,
                'archivos'=> Archivo::selArchivos(),
                'carpetas'=> Carpeta::selCarpetasArchivo($documento->archivo_id),
                'extractos'=> $documento->extractos,
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
    public function destroy(Documento $documento)
    {
       // $this->authorize('delete', $cliente);

        //$documento->extractos()->sync([]);

        $documento->delete();

        if (request()->wantsJson()){
            return [
                'documentos'=> Documento::with(['archivo','carpeta'])->whereYear('fecha',date('Y'))
                            ->orderBy('fecha','desc')
                            ->get(),
                'archivos'  =>  Archivo::selArchivos(),
            ];
        }
    }
}
