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

Route::get('ledger',function() {
return view('templates.ledger');
});

/*Route::get('loginhistory',function() {
return view('templates.loginhistory');
});*/

Route::post('adduser', 'AjaxAddUserController@index');
$router->resource('manageusers','ManageUsersController');
$router->resource('login','LoginController');
$router->resource('home','HomeController');
$router->resource('loginhistory','LoginHistoryController');