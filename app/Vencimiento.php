<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vencimiento extends Model
{
    protected $fillable = [
        'nombre'
    ];

    /**
     *
     *  Duvuelve vencimientos formateados para cargar en select Vuetify
     *
     */
    public static function selVencimientos(){

        return Vencimiento::select('id AS id', 'nombre AS name')
            ->orderBy('nombre', 'asc')
            ->get();

    }
}
