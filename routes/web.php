<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get  ('/password/change', 'UsuarioController@show')->name('profile');
Route::post ('/password/change', 'UsuarioController@passwordChange');

Route::redirect('/', '/panel');
//Route::redirect('/home', '/panel');

Route::get('/import', 'ImportController@edit');
Route::get('/import-inmediatos', 'ImportExpressController@edit');
Route::get('/import-calendario', 'CalendarController@edit');

Route::post('/import/billings', 'ImportController@upload');
Route::post('/import/express',  'ImportExpressController@upload');
Route::post('/import/calendar', 'CalendarController@upload');

Route::get('/import/file', 'ImportController@import');
Route::get('/import/file-inmediatos', 'ImportExpressController@import');

Route::get('/test', 'LayoutController@test');

Route::post('/panel', 'PanelController@showPost');
Route::get ('/panel', 'PanelController@index');

Route::post('/oficina/seleccionar', 'OfficeController@set');

Route::get('/clientes', 'ClientController@index');

Route::post('/clientes/detalle', 'ClientController@postShow');

Route::get('/clientes/comparativa', 'MonthlyClientController@index');


Route::get('/cargar-archivo', 'LayoutController@upload')->middleware('role:manager');
Route::get('/configuracion', 'UsuarioController@settings');
Route::get('/configuracion-defecto', 'UsuarioController@emptySettings');
Route::get('/perfil', 'LayoutController@profile');
Route::get('/analisis', 'LayoutController@analysis');


Route::get('ajax', function(){ return view('ajax'); });
Route::post('/postajax','AjaxController@post');


Route::get('/active-office', function() 
{
	return "Redirect de offina activa";
});


Route::get('/concepts', 'ConceptController@index');

Auth::routes();

Route::get('/controller', 'BillingsController@index');

Route::resource('photos', 'PhotoController');

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

//Clear Config cache:
Route::get('/config-clear', function() {
    $exitCode = Artisan::call('config:clear');
    return '<h1>Clear Config cleared</h1>';
});

//Clear Config cache:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Clear Cache cleared</h1>';
});

//Clear Config cache:
Route::get('/application-key', function() {
    $exitCode = Artisan::call('key:generate');
    return '<h1>Key generated</h1>';
});

//Clear Config cache:
Route::get('/clear-all', function() {
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('config:cache');
    //$exitCode = Composer::call('dump-autoload');
    return '<h1>All creared cleared</h1>';
});
