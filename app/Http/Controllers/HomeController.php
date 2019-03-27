<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

         return view('home');
    }

    public function dash(Request $request)
    {

        $authUser = $request->user();

        // $admin = ($request->user()->hasRole('Root') || $request->user()->hasRole('Admin'));

        $role_user=[];
        $data = User::find($authUser->id)->roles()->get();
        foreach($data as $role){
            $role_user[]=$role->name;
        }

        $permisos_user=[];
        $data = User::find($authUser->id)->permissions()->get();
        foreach($data as $permiso){
            $permisos_user[]=$permiso->name;
        }

        $user = [
            'name' => $authUser->name,
            'username' => $authUser->username,
            'avatar'=> $authUser->avatar,
            'roles' => $role_user,
            'permisos'=> $permisos_user
        ];


        if (request()->wantsJson())
            return (compact('user'));
    }
}
