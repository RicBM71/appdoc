<?php

namespace App;

use App\Carpeta;
use App\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;

class Carpeta extends Model
{
    protected $fillable = [
        'nombre', 'color',
    ];

    protected static function boot()
    {
        parent::boot();

    }

    public function subcarpetas()
    {
        return $this->hasMany(Subcarpeta::class);
    }

     /**
     *
     * @return Array formateado para select Vuetify
     *
     */
    public static function selCarpetas(){

        return Carpeta::select('id AS value', 'nombre AS text')
            ->orderBy('nombre', 'asc')
            ->get();

    }

}
