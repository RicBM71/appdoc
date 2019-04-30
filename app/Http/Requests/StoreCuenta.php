<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCuenta extends FormRequest
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

            'nombre'        => ['required', 'string', 'max:50'],
            'iban'          => ['nullable','iban', 'max:50'],
            'bic'           => ['nullable','bic', 'max:11'],
            'sepa'          => ['nullable','string', 'max:20'],
            'activa'        => ['boolean'],
            'remesa'        => ['boolean'],
        ];

        return $data;
    }
}
