<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retencion extends Model
{
    protected $table = 'retenciones';

    protected $fillable = [
        'nombre', 'importe',
    ];
}
