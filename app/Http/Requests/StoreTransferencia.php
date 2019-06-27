<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransferencia extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data = [

            'concepto'      => ['required', 'string'],
            'fecha'         => ['date','required'],
            'iban_cargo'    => ['nullable','string'],
            'bic_cargo'     => ['nullable','string'],
            'iban_abono'    => ['nullable','string'],
            'bic_abono'     => ['nullable','string'],
            'enviada'       => ['nullable','boolean'],
            'importe'       => ['required','numeric'],
            'cliente_id'    => ['required','numeric'],
        ];

        return $data;
    }
}
