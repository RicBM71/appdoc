<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'razon', 'cif', 'poblacion', 'direccion', 'cpostal','provincia', 'telefono1','telefono2',
        'contacto', 'email', 'web', 'txtpie1', 'txtpie2', 'flags','sigla', 'carpeta','titulo',
        'logo','certificado','passwd_cer', 'carpeta_id','username'
    ];


    public function setCifAttribute($cif)
    {
        $this->attributes['cif'] = strtoupper($cif);

    }

    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);

    }

    public function setWebAttribute($web)
    {
        $this->attributes['web'] = strtolower($web);

    }
}
