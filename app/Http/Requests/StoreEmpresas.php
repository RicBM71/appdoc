<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmpresas extends FormRequest
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

        return [
            'nombre'        => ['required', 'string', 'max:50'],
            'razon'         => ['nullable','string', 'max:50'],
            'cif'           => ['nullable','string', 'max:20', Rule::unique('empresas')->ignore($this->route('empresa')->id)],
            'poblacion'     => ['nullable','string', 'max:5'],
            'direccion'     => ['nullable','string', 'max:50'],
            'cpostal'       => ['nullable','string', 'max:5'],
            'provincia'     => ['nullable','string', 'max:5'],
            'telefono1'     => ['nullable','string', 'max:20'],
            'telefono2'     => ['nullable','string', 'max:20'],
            'contacto'      => ['nullable','string', 'max:50'],
            'email'         => ['nullable','string', 'max:50'],
            'web'           => ['nullable','string', 'max:50'],
            'txtpie1'       => ['nullable','string'],
            'txtpie2'       => ['nullable','string'],
            'flags'         => ['nullable','string', 'max:50'],
            'sigla'         => ['nullable','string', 'max:50'],
            'carpeta'       => ['nullable','string', 'max:50'],
            'titulo'        => ['nullable','string', 'max:50'],
            'logo'          => ['nullable','string', 'max:50'],
            'certificado'   => ['nullable','string', 'max:50'],
            'passwd_cer'    => ['nullable','string', 'max:50'],
            'carpeta_id'    => ['nullable','integer'],
        ];

    }
}
