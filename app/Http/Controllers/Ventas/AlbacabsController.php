<?php

namespace App\Http\Controllers\Ventas;

use PDF;
use App\Iva;
use App\Fpago;
use App\Albacab;
use App\Cliente;
use App\Empresa;
use App\Contador;
use App\Retencion;
use App\Vencimiento;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAlbaranes;
use Illuminate\Support\Facades\Storage;
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
        $this->authorize('create', new Albacab);

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
        $data['empresa_id'] =  session()->get('empresa')->id;
        $data['username'] = $request->user()->username;

        $contador = Contador::incrementaContador($data['ejercicio']);

        $data['serie']= $contador->seriealb;
        $data['albaran']= $contador->albaran;
        $data['ejefac']=0;

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
            $data['ejefac'] = (int) date('Y');
        }else{
            $data['ejefac'] = (int) date('Y',strtotime($data['fecha_fac']));
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

    public function print(Albacab $albacab)
    {
        $empresa  = session()->get('empresa');

        // echo 'file://'.base_path('storage/crt/');
        // echo 'file:///home/sanaval-tec/myapp/storage/crt/sntfirma.crt';
        // return;

        // echo file_exists('file://'.realpath('../storage/crt/'.$empresa->certificado));
        // // echo 'file://'.realpath('../storage/crt/'.$empresa->certificado);
        // // echo '\n';
        // // echo base_path('storage/crt/sntfirma.crt');
        // // echo '\n';
        // return;

        //echo Storage::disk('public')->get();


        //$empresa  = session()->get('empresa');
        // echo realpath('/');
        // echo 'file://'.realpath('../storage/crt/');
        // //echo 'file://'.base_name('../storage/crt/');
        // return;



        PDF::setHeaderCallback(function($pdf) {
            $file = '@'.(Storage::disk('public')->get('logos/'. session()->get('empresa')->logo));
            $pdf->Image($file, 0, 10, 40, 14, 'JPG', null, 'M', true, 300, 'L', false, false, 0, false, false, false);

            // PDF::SetFont('helvetica', 'R', 7);

            // $txt = session()->get('empresa')->razon."\n".
			//        session()->get('empresa')->direccion."\n".
			//        session()->get('empresa')->cpostal." ".session()->get('empresa')->poblacion."\n".
            //        'CIF.: '.session()->get('empresa')->cif;
            // $pdf->Write(0, $txt);

        });

        PDF::setFooterCallback(function($pdf) {

            PDF::SetFont('helvetica', 'R', 7);

            PDF::Write(0, session()->get('empresa')->txtpie1, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
            PDF::Write(0, session()->get('empresa')->txtpie2, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
        });





        $this->setPrepararAlb($empresa);

        $this->setCabeceraAlb();



        PDF::Write(0, 'Hello Worldd');

        PDF::Output('hello_world.pdf');

    }

    private function setPrepararAlb($empresa){

                // set document information
        PDF::SetCreator(PDF_CREATOR);
        PDF::SetAuthor($empresa->nombre);
        PDF::SetTitle('Albarán');
        PDF::SetSubject('1001');

        // set default header data
        //PDF::SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // set header and footer fonts
        PDF::setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        PDF::setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        PDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        PDF::SetHeaderMargin(PDF_MARGIN_HEADER);
        PDF::SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        PDF::setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            PDF::setLanguageArray($l);
        }

        // ---------------------------------------------------------

        // set font
        //PDF::SetFont('times', 'BI', 12);
        PDF::SetFont('helvetica', '', 9, '', false);

        // add a page
        PDF::AddPage();

        $clave_firma = 'file://'.realpath('../storage/crt/'.$empresa->certificado);
        $clave_firma = 'file://'.base_path('storage/crt/'.$empresa->certificado);

        //$clave_firma = 'file:///home/sanaval-tec/myapp/storage/crt/sntfirma.crt';
        $clave_privada = $clave_firma;
        $info = array('Name' => 'CIF '.$empresa->cif,
                'Location' => $empresa->poblacion,
                'Reason' =>  $empresa->razon,
                'ContactInfo' => $empresa->email);


         PDF::setSignature($clave_firma,$clave_privada,'delta00',"",1,$info);
         PDF::setSignatureAppearance(10,10,10,10,-1);

    }

    private function setCabeceraAlb(){

        $y = PDF::getY();

        $txt = "cliente";

		PDF::SetX(115);
		PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

		$txt = "direccion";
		PDF::SetX(115);
		PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

		$txt = "postal pobla";
		PDF::SetX(115);
		PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        if (false){
		    $txt = "provincia";
		    PDF::SetX(115);
		    PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));
        }

    }


}
