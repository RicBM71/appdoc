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
        'fpago_id','factusn','iban','ref19','username'
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

       // establecemos la relación muchos a muchos
       public function users()
       {
           return $this->belongsToMany(User::class);
       }
}

