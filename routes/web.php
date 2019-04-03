<?php


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/dash', 'HomeController@dash')->name('dash');

Route::get('/test', function(){
 	return  App\Retencion::all();
});


Route::group([
    //'as' => '.admin' ver php artisan r:l para ver problema admin.admin.
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => 'auth'],
    function (){
        Route::resource('users', 'UsersController', ['as' => 'admin']);
        Route::post('users/password/update', 'UsersController@updatePassword');
        Route::resource('roles', 'RolesController', ['as' => 'admin']);
        Route::resource('permissions', 'PermissionsController', ['except'=>'show', 'as' => 'admin']);

        Route::middleware('role:Root|Admin')
            ->put('users/{user}/roles','UsersRolesController@update');
        Route::middleware('role:Root|Admin')
            ->put('users/{user}/permissions','UsersPermissionsController@update');
        Route::middleware('role:Root|Admin')
            ->put('users/{user}/empresas','UsersEmpresasController@update');
        Route::put('users/{user}/empresa', 'UsersController@updateEmpresa');

        Route::post('users/{user}/avatar', 'AvatarsController@store');
        Route::delete('avatars/{user}/delete', 'AvatarsController@destroy');

        Route::middleware('role:Root|Admin')->group(function () {
            Route::resource('retenciones', 'RetencionesController', ['except'=>'show','as' => 'admin']);
            Route::resource('ivas', 'IvasController', ['except'=>'show','as' => 'admin']);
            Route::resource('carpetas', 'CarpetasController', ['except'=>'show','as' => 'admin']);
            Route::resource('empresas', 'EmpresasController', ['except'=>'show','as' => 'admin']);
            Route::resource('fpagos', 'FpagosController', ['except'=>'show','as' => 'admin']);
            Route::resource('contadors', 'ContadorsController', ['except'=>'show','as' => 'admin']);
        });



    }
);


Route::group([
    'prefix' => 'mto',
    'namespace' => 'Mto',
    'middleware' => 'auth'],
    function (){
        Route::resource('clientes', 'ClientesController', ['except'=>'show','as' => 'mto']);
        Route::resource('productos', 'ProductosController', ['except'=>'show','as' => 'mto']);
        Route::resource('vencimientos', 'VencimientosController', ['except'=>'show','as' => 'mto']);
    }
);


Route::group([
    'prefix' => 'ventas',
    'namespace' => 'Ventas',
    'middleware' => 'auth'],
    function (){
        Route::resource('albacabs', 'AlbacabsController', ['except'=>'show','as' => 'ventas']);
    }
);


Route::any('{all}', function () {
    return view('welcome');
})->where(['all' => '.*']);
