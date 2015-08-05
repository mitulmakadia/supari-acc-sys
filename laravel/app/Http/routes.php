<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/', function () {
//    return view('welcome');
//});
//
//Route::resource('dogs','dogsController');
//
//Route::get('login', function() {
//  return View::make('login');
//});
////POST route
//Route::post('login', 'AccountController@login');
//
//Route::get('login','AccountController@index');
//
//
/*Route::bind('songs',function($slug){
	return App\Song::whereSlug($slug)->first();
});
Route::get('songs','SongsController@index');
Route::get('songs/{slug}','SongsController@show');
Route::get('songs/{slug}/edit','SongsController@edit');
Route::patch('songs/{slug}','songsController@update');*/

$router->resource('songs','SongsController');

