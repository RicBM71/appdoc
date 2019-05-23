<?php

namespace App\Http\Controllers\Mto;

use App\Filedoc;
use App\Documento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FiledocsController extends Controller
{
    public function store($documento_id){

        $this->validate(request(),[
            'files' => 'required'
        ]);

        //return request()->file('files');

        $files = request()->file('files')->store('documentum','local');


    	$filesUrl = Storage::url($files);

    	// 	//insert en la tabla photos
    	Filedoc::create([
             'url'	=> $filesUrl,
             'empresa_id' => session()->get('empresa')->id,
             'documento_id' => $documento_id,
             'username' => request()->user()->username
        ]);

        return [
            'files' => Filedoc::FilesDocumento($documento_id)->get()
        ];
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Filedoc $filedoc)
    {
        $filedoc->delete();

        if (request()->wantsJson()){
            return [
                'files' => Filedoc::FilesDocumento($filedoc->documento_id)->get()
            ];
        }
    }

    public function show(Filedoc $filedoc){

        $ficheroPath = str_replace('/storage', '', $filedoc->url);

        $ext = explode('.',$ficheroPath);
        $ext = array_pop($ext);

        $headers = [
            "ResponseContentType", "application/pdf",
        ];

        // header('Content-type: application/pdf');
        // header('Content-Disposition: attachment; filename="aa.pdf"');
        // readfile(Storage::download($ficheroPath));

        // return;

        return Storage::download($ficheroPath,'file.'.$ext);

        return [
            'file' => Storage::download($ficheroPath),
            'ext' => 'pdf'
        ] ;

        //\Storage::disk('local')->put('remesa.xml',$xml);
    }
}
