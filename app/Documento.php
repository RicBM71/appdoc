<?php

namespace App;

use App\Scopes\EmpresaScope;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasRoles;

    protected $dates =['fecha'];

    protected $fillable = [
        'empresa_id','archivo_id','carpeta_id','fecha', 'concepto', 'cerrado','username',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaScope);
    }

    public function archivo()
    {
    	return $this->belongsTo(Archivo::class);
    }

    public function carpeta()
    {
    	return $this->belongsTo(Carpeta::class);
    }
}
