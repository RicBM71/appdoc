<?php

namespace App\Policies;

use App\User;
use App\Albacab;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlbacabPolicy
{
    use HandlesAuthorization;

    public function before($authUser)
    {
            // con esto ya no pasa por ningún otro método, lo dejo por si lo necesito para más adelante.
        if($authUser->hasRole('Admin')){
            return true;
        }
    }


    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $authUser)
    {
        return $authUser->hasRole('Usuario') ?: $this->deny("Acceso denegado. Role de Usuario requerido");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Albacab  $model
     * @return mixed
     */
    public function update(User $authUser, Albacab $albacab)
    {

        if ($authUser->hasRole('Admin') && $albacab->ejefac == 0)
            return true;
        else if ($authUser->hasRole('Usuario') &&
                $albacab->username === $authUser->username &&
                $albacab->ejefact > 0 &&
                $albacab->fecha_fac == date('Y-m-d'))
                return true;
        else if ($authUser->hasRole('Usuario') &&
                $albacab->ejefact == 0 )
                return true;
       else
            return $this->deny("Acceso denegado. No tiene permisos para editar el albarán.");


    }

     /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Albacab  $model
     * @return mixed
     */
    public function delete(User $authUser, Albacab $albacab)
    {

        // \Log::info($albacab->username."-".$authUser->username);
        // \Log::info($albacab);
        // \Log::info($albacab->ejefact);
        // \Log::info($albacab->fecha_alb."-".date('Y-m-d'));

        if ($authUser->hasRole('Admin') && is_null($albacab->ejefac))
            return true;
        else if ($authUser->hasRole('Usuario') &&
                $albacab->username === $authUser->username &&
                $albacab->ejefact == 0 &&
                $albacab->fecha_alb == date('Y-m-d'))
                return true;
       else
            return $this->deny("Acceso denegado. No tiene permisos para borrar el albarán.");

    }


}