<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Albalin extends Model
{
    protected $fillable = [
        'albacab_id','producto_id','nombre', 'unidades', 'impuni', 'poriva', 'porirpf', 'dto',
        'importe', 'username',
    ];

    public function producto()
    {
    	return $this->belongsTo(Producto::class);
    }
    // public function retencion()
    // {
    // 	return $this->belongsTo(Retencion::class);
    // }
    // public function iva()
    // {
    // 	return $this->belongsTo(Iva::class);
    // }

    public function scopeAlbacab($query, $albacab_id)
    {
        // esto permite relacionar retencion e iva en el objeto producto, aquí no hace falta pero lo usaremos.
        //$query->with(['producto','producto.retencion','producto.iva'])
        $query->with(['producto'])
                    ->where('albacab_id', '=', $albacab_id );

    }

    public static function totalAlbaran($id){
        return DB::table('albalins')
                ->select(DB::raw('SUM(unidades) AS unidades, ROUND(SUM(importe*(poriva/100)), 2) AS impiva, ROUND(SUM(importe*(porirpf/100)), 2) AS impirpf, SUM(importe) AS importe'))
                ->where('albacab_id', $id)
                ->first();
    }
}
