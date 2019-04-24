<?php

namespace App\Http\Controllers\Ventas;

use PDF;
use App\Iva;
use App\Fpago;
use App\Albacab;
use App\Albalin;
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

        $data['empresa_id'] =  session()->get('empresa_id');
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

    public function print($id)
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

            $texto = 'Sus datos de carácter personal han sido recogidos de acuerdo con lo dispuesto en el Reglamento (UE) 2016/679 del '.
                    'Parlamento Europeo y del Consejo, de 27 de abril de 2016, relativo a la protección de las personas físicas en lo que '.
                    'respecta al tratamiento de datos personales y a la libre circulación de los mismos. '.
                    'Le ponemos en conocimiento que estos datos se encuentran almacenados en un fichero propiedad de %e. '.
                    'De acuerdo con la Ley anterior, tiene derecho a ejercer los derechos de acceso, rectificación, cancelación, limitación, '.
                    'oposición y portabilidad de manera gratuita mediante correo electrónico a: %m o bien en la '.
                    'siguiente dirección: '.session()->get('empresa')->direccion.' '. session()->get('empresa')->cpostal.' '. session()->get('empresa')->polbacion.".\n";

            $texto = str_replace('%e',  session()->get('empresa')->razon, $texto);
            $texto = str_replace('%m',  session()->get('empresa')->email, $texto);

            PDF::Write(0, $texto, $link='', $fill=0, $align='J', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
            PDF::Ln();

            PDF::SetFont('helvetica', 'R', 8);

            PDF::Write(0, session()->get('empresa')->txtpie1, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
            PDF::Write(0, session()->get('empresa')->txtpie2, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
        });





        $this->setPrepararAlb($empresa);

        $data =  Albacab::with(['cliente','fpago','vencimiento','albalins'])->find($id);

        // cabecera cliente
        $this->setCabeceraCli($data->cliente);

        // cabecera albarán, cif y fecha y número

        $this->setCabeceraAlb($data);

        // body albarán, líneas.

        $this->setLineasAlb($data->albalins);

        $totales = Albalin::totalLineasByAlb($id);

        // totales albarán

        $this->setTotalesAlb($totales);

        // forma de pago, vencimiento
        $this->setPieAlb($data);


        //PDF::Write(0, 'Hello Worldd');

        PDF::Output($data->factura.'.pdf');

    }

    /**
     *
     * @param Model Empresa
     *
     */
    private function setPrepararAlb($empresa){

                // set document information
        PDF::SetCreator(PDF_CREATOR);
        PDF::SetAuthor($empresa->nombre);
        PDF::SetTitle('Albarán');
        PDF::SetSubject('');

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
        //PDF::SetFooterMargin(PDF_MARGIN_FOOTER);
        PDF::SetFooterMargin(34);

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

        PDF::SetFont('helvetica', '', 9, '', false);

        // add a page
        PDF::AddPage();

        (config('app.env') == "production")
            ? $clave_firma = 'file://'.base_path('storage/crt/'.$empresa->certificado)
            : $clave_firma = 'file://'.realpath('../storage/crt/'.$empresa->certificado);


        //$clave_firma = 'file:///home/sanaval-tec/myapp/storage/crt/sntfirma.crt';
        $clave_privada = $clave_firma;
        $info = array('Name' => 'CIF '.$empresa->cif,
                'Location' => $empresa->poblacion,
                'Reason' =>  $empresa->razon,
                'ContactInfo' => $empresa->email);


         PDF::setSignature($clave_firma,$clave_privada,$empresa->passwd_cer,"",1,$info);
         PDF::setSignatureAppearance(10,10,10,10,-1);

    }

    /**
     *
     * @param Model Cliente
     *
     */
    private function setCabeceraCli($cliente){

		// recuadro cliente
		PDF::SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(4, 4, 180)));
		PDF::RoundedRect(110, 22, 86, 24, 3.50, '1111', '');

        $y = PDF::getY();

        $txt = $cliente->razon;

		PDF::SetX(115);
		PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

		$txt = $cliente->direccion;
		PDF::SetX(115);
		PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

		$txt = $cliente->cpostal.' '.$cliente->poblacion;
		PDF::SetX(115);
		PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        if ($cliente->poblacion != $cliente->provincia){
		    $txt = $cliente->provincia;
		    PDF::SetX(115);
		    PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));
        }

    }

    /**
     *
     * @param Model Albaran
     *
     */
    private function setCabeceraAlb($data){


        if ($data->ejefac == 0){
            $texto = "ALBARÁN";
            $numero = $data->albser;
            $fecha = getFecha($data->fecha_alb);
        }else{
            $texto = "FACTURA";
            $numero = $data->factura;
            $fecha = getFecha($data->fecha_fac);
        }


		PDF::SetY(60);
		PDF::SetFont('helvetica', '', 9, '', false);
		PDF::MultiCell(20,8,"CIF", '1', 'C', 0, 0, '', '', true,0,false,true,8,'M',false);
		PDF::SetFont('helvetica', 'B', 9, '', false);
		PDF::MultiCell(36,8,$data->cliente->cif, '1', 'C', 0, 0, '', '', true,0,false,true,8,'M',false);
		PDF::SetFont('helvetica', '', 9, '', false);
		PDF::MultiCell(28,8,"FECHA", '1', 'C', 0, 0, '', '', true,0,false,true,8,'M',false);
		PDF::SetFont('helvetica', 'B', 9, '', false);
		PDF::MultiCell(36,8,$fecha, '1', 'C', 0, 0, '', '', true,0,false,true,8,'M',false);
		PDF::SetFont('helvetica', '', 9, '', false);
		PDF::MultiCell(28,8,$texto, '1', 'C', 0, 0, '', '', true,0,false,true,8,'M',false);
		PDF::SetFont('helvetica', 'B', 9, '', false);
		PDF::MultiCell(36,8,$numero, '1', 'C', 0, 0, '', '', true,0,false,true,8,'M',false);

        // body - cabecera de líneas
		PDF::SetY(74);
		PDF::SetFont('helvetica', 'B', 9, '', false);

		PDF::MultiCell(104,8,("Descripción"), '1', 'C', 0, 0, '', '', true,0,false,true,8,'M',false);
		PDF::MultiCell(12,8,'Ud.', '1', 'C', 0, 0, '', '', true,0,false,true,8,'M',false);
		PDF::MultiCell(20,8,'Imp Ud', '1', 'C', 0, 0, '', '', true,0,false,true,8,'M',false);
		PDF::MultiCell(12,8,'% Dto', '1', 'C', 0, 0, '', '', true,0,false,true,8,'M',false);
		PDF::MultiCell(12,8,'% Iva', '1', 'C', 0, 0, '', '', true,0,false,true,8,'M',false);
		PDF::MultiCell(24,8,'Importe', '1', 'C', 0, 0, '', '', true,0,false,true,8,'M',false);

    }

    /**
     *
     * @param Model Albalins
     *
     */
    private function setLineasAlb($lineas){

        //PDF::SetFont('helvetica', '', 9, '', false);
		PDF::Ln();
		$linea = $total =0;
		foreach ($lineas as $row){
			$linea++;
			$nombre = $row->producto->nombre.' '.$row->descripcion;
			PDF::MultiCell(104,6,$nombre, 'LR', 'L', 0, 0, '', '', true,0,false,true,6,'M',false);
			PDF::MultiCell(12,6,getDecimal($row->unidades,0), 'LR', 'R', 0, 0, '', '', true,0,false,true,6,'M',false);
			PDF::MultiCell(20,6,getDecimal($row->impuni), 'LR', 'R', 0, 0, '', '', true,0,false,true,6,'M',false);
			PDF::MultiCell(12,6,getDecimal($row->impdto), 'LR', 'R', 0, 0, '', '', true,0,false,true,6,'M',false);
			PDF::MultiCell(12,6,getDecimal($row->impiva), 'LR', 'R', 0, 0, '', '', true,0,false,true,6,'M',false);
			PDF::MultiCell(24,6,getDecimal($row->importe), 'LR', 'R', 0, 0, '', '', true,0,false,true,6,'M',false);
            PDF::Ln();
            $total += $row->importe;
		}

		$trazo='LR';
		while ($linea < 18){
			$linea++;
			if ($linea==18)
				$trazo='LRB';
			PDF::MultiCell(104,6,'', $trazo, 'L', 0, 0, '', '', true,0,false,true,6,'M',false);
			PDF::MultiCell(12,6,'', $trazo, 'L', 0, 0, '', '', true,0,false,true,6,'M',false);
			PDF::MultiCell(20,6,'', $trazo, 'L', 0, 0, '', '', true,0,false,true,6,'M',false);
			PDF::MultiCell(12,6,'', $trazo, 'L', 0, 0, '', '', true,0,false,true,6,'M',false);
			PDF::MultiCell(12,6,'', $trazo, 'L', 0, 0, '', '', true,0,false,true,6,'M',false);
			PDF::MultiCell(24,6,'', $trazo, 'L', 0, 0, '', '', true,0,false,true,6,'M',false);
            PDF::Ln();
		}
		$trazo='';
		PDF::MultiCell(128,6,'', $trazo, 'L', 0, 0, '', '', true,0,false,true,6,'M',false);
		$trazo='LRB';
        PDF::MultiCell(32,6,'Subtotal', $trazo, 'R', 0, 0, '', '', true,0,false,true,6,'M',false);

		PDF::MultiCell(24,6, getCurrency($total), $trazo, 'R', 0, 0, '', '', true,0,false,true,6,'M',false);

    }

    /**
     *
     * @param Model Albalins
     *
     */
    private function setTotalesAlb($totales){

        $trazo='LRB';

        if ($totales->iva<>0){
			PDF::Ln();
			PDF::MultiCell(128,6,'', '', 'L', 0, 0, '', '', true,0,false,true,6,'M',false);
			PDF::MultiCell(32,6,'IVA '.getCurrency($totales->poriva,'%'), $trazo, 'R', 0, 0, '', '', true,0,false,true,6,'M',false);
			PDF::MultiCell(24,6,getCurrency($totales->iva), $trazo, 'R', 0, 0, '', '', true,0,false,true,6,'M',false);
		}
		if ($totales->irpf<>0){
			PDF::Ln();
			PDF::MultiCell(128,6,'', '', 'L', 0, 0, '', '', true,0,false,true,6,'M',false);
			PDF::MultiCell(32,6,'IRPF '.getCurrency($totales->porirpf,'%'), $trazo, 'R', 0, 0, '', '', true,0,false,true,6,'M',false);
			PDF::MultiCell(24,6,getCurrency($totales->irpf), $trazo, 'R', 0, 0, '', '', true,0,false,true,6,'M',false);
		}

		PDF::SetFont('helvetica', 'B', 9, '', false);
		PDF::Ln();
		PDF::MultiCell(128,6,'', '', 'L', 0, 0, '', '', true,0,false,true,6,'M',false);
		PDF::MultiCell(32,6,'TOTAL', $trazo, 'R', 0, 0, '', '', true,0,false,true,6,'M',false);
		PDF::MultiCell(24,6,getCurrency($totales->importe), $trazo, 'R', 0, 0, '', '', true,0,false,true,6,'M',false);

		PDF::SetFont('helvetica', '', 9, '', false);

		if ($totales->iva==0)
			$texto='*IVA: Actividad exenta según artículo 20 IVA';
		else
			$texto="";

		PDF::Ln();
		PDF::MultiCell(114,6,$texto, '', '', 0, 1, '', '', true,0,false,true,6,'M',false);

    }

      /**
     *
     * @param Model Albaran
     *
     */
    private function setPieAlb($data){

        PDF::MultiCell(30,8,'Forma de Pago', '1', 'C', 0, 0, '', '', true,0,false,true,8,'M',false);
		PDF::MultiCell(60,8,$data->fpago->nombre, '1', 'L', 0, 0, '', '', true,0,false,true,8,'M',false);
        PDF::MultiCell(26,8,'IBAN', '1', 'C', 0, 0, '', '', true,0,false,true,8,'M',false);
        PDF::MultiCell(68,8,$data->cliente->iban, '1', 'C', 0, 1, '', '', true,0,false,true,8,'M',false);

        PDF::Ln();
        PDF::MultiCell(184,24,$data->notas, '1', 'L', 0, 0, '', '', true,0,false,true,24,'T',false);


    }

}
