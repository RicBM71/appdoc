<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Iva extends Model
{
    protected $fillable = [
        'nombre', 'importe',
    ];

}
