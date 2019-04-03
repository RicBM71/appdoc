<?php

namespace App;

use App\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;

class Albacab extends Model
{
    protected $dates =['fecha_alb','fecha_fac'];

    protected $appends = ['alb_ser'];

    protected $fillable = [
        'empresa_id','ejercicio','albaran', 'serie', 'fecha_alb', 'cliente_id', 'ejefac', 'factura', 'fecha_fac',
        'fpago_id', 'vencimiento_id', 'notificado', 'notas','username','created_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaScope);
    }

    public function cliente()
    {
    	return ($this->belongsTo(Cliente::class));
    }

    public function fpagos()
    {
    	return $this->belongsTo(Fpago::class);
    }

    public function albalins()
    {
        return $this->hasMany(Albalin::class);
    }

    public function getAlbSerAttribute(){


        return $this->serie.'-'.str_pad($this->albaran, 4, "0", STR_PAD_LEFT);

    }

}
