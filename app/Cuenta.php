<?php

namespace App;

use App\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{

    protected $fillable = [
        'empresa_id','nombre', 'iban', 'bic', 'sepa','activa', 'remesa', 'username'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaScope);
    }

    public static function selCuentas(){

        return Cuenta::select('id AS value', 'nombre AS text')
            ->where('activa',1)
            ->orderBy('nombre', 'asc')
            ->get();
    }
}
