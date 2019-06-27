<?php

namespace App\Http\Controllers\Admin;

use App\Cuenta;
use App\Cliente;
use App\Transferencia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Digitick\Sepa\TransferFile\Factory\TransferFileFacadeFactory;


class SepaController extends Controller
{
    public function index(){

        // if (!auth()->user()->hasRole('Admin'))
        //     abort(403,'No Autorizado');

        // $transferencias =  Transferencia::with(['cliente'])->get();
        // $remesa = $this->generarTransfer($transferencias,2,'2019-06-27');
        // return $remesa;

        if (request()->wantsJson())
            return ['cuentas'=> Cuenta::selCuentas()];
    }

    public function transfer(Request $request){


        $data = $request->validate([
            'cuenta_id'=>'required|integer',
            'fecha'=>'required|date'
        ]);


        $transferencias =  Transferencia::with(['cliente'])->remesar($data['fecha'])->get();

        if (count($transferencias)==0)
            abort(404,'No hay transferencias para remesar');

        $remesa = $this->generarTransfer($transferencias,$data['cuenta_id'],$data['fecha']);

        if (request()->wantsJson())
            return [
                'xml'       => $remesa['xml'],
                'importe'   => $remesa['importe'],
                'adeudos'   => $remesa['adeudos']
            ];

    }

     /**
     * Vamos a generar el fichero de remesa.
     */
    private function generarTransfer($data, $cuenta_id, $fecha){


        $cuenta = Cuenta::find($cuenta_id);


        //Set the initial information
        $customerCredit = TransferFileFacadeFactory::createCustomerCredit(session()->get('empresa')->cif, session()->get('empresa')->razon);

        $idPayment = session()->get('empresa')->cif."000";

        $customerCredit->addPaymentInfo($idPayment, array(
            'id'                      => $idPayment,
            'debtorName'              => session()->get('empresa')->razon,
            'debtorAccountIBAN'       => $cuenta->iban,
            'debtorAgentBIC'          => $cuenta->bic,
        ));

        $imp_total_remesa = $adeudos = 0;
        foreach ($data as $row){

            $t = [
                'enviada' => true,
                'username' => session()->get('username')
            ];
            $row->update($t);

            $adeudos++;
            // create a payment, it's possible to create multiple payments,
            // "firstPayment" is the identifier for the transactions

            $imp_total_remesa += $row->importe;

                // Add a Single Transaction to the named payment
            $customerCredit->addTransfer($idPayment, array(
                'amount'                  => $row->importe,
                'creditorIban'            => $row->iban_abono,
                'creditorBic'             => $row->bic_abono,
                'creditorName'            => $row->cliente->razon,
                'remittanceInformation'   => $row->concepto
            ));

        }

        $xml = $customerCredit->asXML();

        return [
            'xml' => $xml,
            'importe' => $imp_total_remesa,
            'adeudos' => $adeudos
        ];

        // \Storage::disk('local')->put('remesa.xml',$xml);

        // \Storage::disk('local')->download('remesa.xml');

    }
}
