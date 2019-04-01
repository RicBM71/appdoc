<?php

namespace App;

use App\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;

class Contador extends Model
{

    protected $fillable = [
        'empresa_id', 'ejercicio','albaran', 'seriealb', 'factura', 'seriefac', 'abono', 'serieabo','username',
    ];

    /**
     *
     * Añadimos global scope para filtrado por empresa.
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaScope);
    }
}
