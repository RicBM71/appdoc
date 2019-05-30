<?php

namespace App;

use App\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;

class Extracto extends Model
{

    protected $dates =['fecha'];

    protected $fillable = [
        'empresa_id','cuenta_id', 'concepto', 'dh', 'fecha','importe','username'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaScope);
    }

    public function documentos(){

        return $this->belongsToMany(Documento::class);

    }

}
