<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDocumento extends FormRequest
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

            'concepto'        => ['required', 'string', 'max:50'],
            'fecha'         => ['date','required'],
            'importe'     => ['nullable','numeric'],
            'archivo_id'     => ['required','numeric'],
        ];

        return $data;
    }
}
