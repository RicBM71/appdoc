<?php

namespace App;

use App\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;

class Carpeta extends Model
{

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaScope);
    }


    protected $fillable = [
        'empresa_id', 'archivo_id', 'nombre', 'color',
    ];

    // una carpeta tiene un archivo y solo uno
    public function archivo()
    {
        return $this->belongsTo(Archivo::class);
    }

    // public function scopeArchivo($query, $archivo_id)
    // {

    //     return $query->where('archivo_id', '=', $archivo_id);

    // }

}
