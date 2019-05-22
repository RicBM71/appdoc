<?php

namespace App;

use App\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;

class Filedoc extends Model
{
    protected $fillable = [
        'documento_id','empresa_id','url','username',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaScope);
    }

    public function scopeFilesDocumento($query, $documento_id)
    {

        return $query->where('documento_id', '=', $documento_id);



    }

}
