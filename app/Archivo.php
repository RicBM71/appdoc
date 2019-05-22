<?php

namespace App;

use App\Archivo;
use App\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    protected $fillable = [
        'nombre', 'color',
    ];

    protected static function boot()
    {
        parent::boot();

    }

    public function carpetas()
    {
        return $this->hasMany(Carpeta::class);
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }
     /**
     *
     * @return Array formateado para select Vuetify
     *
     */
    public static function selArchivos(){

        return Archivo::select('id AS value', 'nombre AS text')
            ->orderBy('nombre', 'asc')
            ->get();

    }

}
