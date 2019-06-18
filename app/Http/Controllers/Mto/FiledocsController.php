<?php

namespace App\Http\Controllers\Mto;

use App\Filedoc;
use App\Documento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FiledocsController extends Controller
{
    public function store($documento_id){

        $this->validate(request(),[
            'files' => 'required'
        ]);

        //return request()->file('files');

        $documento = Documento::with(['archivo','carpeta'])->find($documento_id);

        $ejercicio = date('Y',strtotime($documento->fecha));

        $path = 'documentum/'.session()->get('empresa')->path_archivo.'/'.$ejercicio.'/'.$documento->archivo->path.'/'.$documento->carpeta->path;

        $files = request()->file('files')->store($path,'local');


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

        $filename = 'docu'.date('Ymd').'.'.$ext;

        $img = ['pdf','jpg','jpeg','png','gif'];

        if (in_array($ext, $img)) // este abre el fichero en el navegador
            return response()->file(storage_path('/app/' . $ficheroPath));
        else
            return Storage::download($ficheroPath, $filename);

        //return response()->download(storage_path('/app/' . $ficheroPath,$filename));

    }


    public function download($id){

        $zip_file = storage_path('zip/filedoc.zip');


        if (file_exists(storage_path('zip'))==false)
            mkdir(storage_path('zip'), '0755');


        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);



    //     $path = storage_path('invoices');

        $i = 0;

        $files = Documento::with('filedocs')->whereYear('fecha',2019)->get();
        //return $files;

        foreach ($files as $doc){
           // dd($doc->filedocs);

            foreach ($doc->filedocs as $file){
                $ficheroPath = str_replace('/storage', 'app', $file->url);
                //return storage_path($ficheroPath);
                $zip->addFile(storage_path($ficheroPath), $i.'.pdf');
                //$zip->add($ficheroPath);
               // echo storage_path($ficheroPath);
                //return;
                $i++;
               // echo $zip->addFile(storage_path($ficheroPath), $i.".pdf");

            }


            if ($i >  10) break;


        }

        $zip->close();

        // We return the file immediately after download
        return response()->download($zip_file);

    }

}
