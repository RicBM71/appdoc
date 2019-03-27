<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carpeta extends Model
{
    protected $fillable = [
        'empresa','nombre', 'color',
    ];

}
