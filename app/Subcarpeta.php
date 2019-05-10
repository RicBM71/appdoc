<?php

namespace App;

use App\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;

class Subcarpeta extends Model
{

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaScope);
    }


    protected $fillable = [
        'empresa_id', 'carpeta_id', 'nombre', 'color',
    ];

    // una subcarpeta tiene una carpeta
    public function carpeta()
    {
        return $this->belongsTo(Carpeta::class);
    }

    // public function scopeCarpeta($query, $carpeta_id)
    // {

    //     return $query->where('carpeta_id', '=', $carpeta_id);

    // }

}
