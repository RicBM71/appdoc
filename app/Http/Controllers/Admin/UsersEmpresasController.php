<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersEmpresasController extends Controller
{

	public function update(Request $request, User $user)
    {
        //\Log::info('enviando mensaje...');
        //return $request;

        $user->syncEmpresas($request->get('empresas'));

        return response('Las empresas fueron actualizadas',200);
    }
}
