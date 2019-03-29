<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Cliente extends Model
{
    protected $dates =['fechalta','fechabaja'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaScope);
    }

    // public function scopePorEmpresa($query)
    // {
    //     //

    //     $empresa_id =  session()->get('empresa');

    //     return $query->where('empresa_id', '=', $empresa_id);



    // }
}

