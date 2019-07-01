<?php

namespace App\Http\Controllers\Ventas;

use App\Cuenta;
use App\Albacab;
use App\Albalin;
use Illuminate\Http\Request;
use Digitick\Sepa\GroupHeader;
use App\Http\Controllers\Controller;

use Digitick\Sepa\PaymentInformation;
use Illuminate\Support\Facades\Storage;
use Digitick\Sepa\TransferFile\Factory\TransferFileFacadeFactory;


class AdeudosController extends Controller
{
     /**
     * Selección previa remesa
     */
    public function remesa(){

        if (!auth()->user()->hasRole('Admin'))
            abort(403,'No Autorizado');

        if (request()->wantsJson())
            return ['cuentas'=> Cuenta::selCuentas()];
    }

    public function remesar(Request $request){

        if (!auth()->user()->hasRole('Admin'))
            abort(403,'No Autorizado');

        $data = $request->validate([
            'cuenta_id'=>'required|integer',
            'fecha'=>'required|date'
        ]);

        $alb =  Albacab::remesarFacturas($data['fecha']);

        $remesa = $this->generarRemesa($alb,$data['cuenta_id'],$data['fecha']);

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
    private function generarRemesa($data, $cuenta_id, $fecha){


        $cuenta = Cuenta::find($cuenta_id);

        // firstPayment,
        $PmtInfId =  session()->get('empresa')->cif.'000'.date('Ymdhisv');

        $header = new GroupHeader(date('Y-m-d-H-i-s'), session()->get('empresa')->razon);
        $header->setInitiatingPartyId($cuenta->sepa);

        $directDebit = TransferFileFacadeFactory::createDirectDebitWithGroupHeader($header, 'pain.008.001.02');
        //$directDebit = TransferFileFacadeFactory::createDirectDebit(session()->get('empresa')->cif.'000', $cuenta->sepa, 'pain.008.001.02');

        // create a payment, it's possible to create multiple payments,
        // "firstPayment" is the identifier for the transactions
        // This creates a one time debit. If needed change use ::S_FIRST, ::S_RECURRING or ::S_FINAL respectively
        $directDebit->addPaymentInfo($PmtInfId, array(
            'id'                    => $PmtInfId,
            'dueDate'               => new \DateTime(), // optional. Otherwise default period is used
            'creditorName'          => session()->get('empresa')->razon,
            'creditorAccountIBAN'   => $cuenta->iban,
            'creditorAgentBIC'      => $cuenta->bic,
            'seqType'               => PaymentInformation::S_ONEOFF,
            'creditorId'            => $cuenta->sepa,
            'localInstrumentCode'   => 'CORE' // default. optional.
        ));

        $imp_total_remesa = $adeudos = 0;
        foreach ($data as $row){

            $total = Albalin::totalLineasByAlb($row->id);

            $imp_total_remesa += $total->importe;
            $adeudos++;

            $directDebit->addTransfer($PmtInfId, array(
                'amount'                => $total->importe,
                'debtorIban'            => $row->cliente->iban,
                'debtorBic'             => $row->cliente->bic,
                'debtorName'            => $row->cliente->razon,
                'debtorMandate'         => $row->cliente->ref19,
                'debtorMandateSignDate' => $row->fecha_fac,
                'remittanceInformation' => 'Factura',
                'endToEndId'            => $row->factura
            ));

        }

        $xml = $directDebit->asXML();

        return [
            'xml' => $xml,
            'importe' => $imp_total_remesa,
            'adeudos' => $adeudos
        ];

        // \Storage::disk('local')->put('remesa.xml',$xml);

        // \Storage::disk('local')->download('remesa.xml');

    }



}
