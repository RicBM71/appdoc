<?php

namespace App;

use App\Scopes\EmpresaScope;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{

    use HasRoles;

    protected $fillable = [
        'empresa_id','nombre', 'razon', 'cif', 'poblacion', 'direccion', 'cpostal','provincia', 'telefono1','telefono2','tfmovil',
        'fechaalta','fechabaja','web','carpeta_id','patron','notas1','efact','eusr','epass','contacto','email','patron',
        'fpago_id','factura','iban','bic','ref19','username'
    ];


    protected $dates =['fechalta','fechabaja'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaScope);
    }
    public function setCifAttribute($cif)
    {
        $this->attributes['cif'] = strtoupper($cif);

    }
    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);

    }

    public function setWebAttribute($web)
    {
        $this->attributes['web'] = strtolower($web);

    }

    public function setIbanAttribute($iban)
    {
        $this->attributes['iban'] = strtoupper($iban);

    }

    public function setBicAttribute($bic)
    {
        $this->attributes['bic'] = strtoupper($bic);

    }

    public function albacabs()
    {

         return $this->hasMany(Albacabs::class);

    }

    public function scopeFacturables($query)
    {

        return $query->where('factura', true);

            // no sé que pintaba esto aquí de momento fuera.
            // return $query->where('factura', '=', true)
            //              ->where('nombre', 'LIKE', '%' . $nombre . '%');

    }

    public function scopeConPatron($query){
        return $query->where('patron', '>', '');
    }

    /**
     *
     * Selecciona clientes facturables y prepara para JSON select
     *
     */

    public static function selClientesFacturables(){

        return Cliente::select('id AS value', 'nombre AS text')->facturables()->get();

    }

       // establecemos la relación muchos a muchos
    //    public function users()
    //    {
    //        return $this->belongsToMany(User::class);
    //    }
}

