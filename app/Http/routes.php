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

Route::get( '/', function () {
    return view( 'welcome' );
} );

// Authentication routes
Route::post( 'auth/login', 'Auth\AuthController@postLogin' );
Route::get( 'auth/login', 'Auth\AuthController@getLogin' );
Route::get( 'auth/logout', 'Auth\AuthController@getLogout' );

/*
| Object routes
*/
Route::resource( 'objects', 'ObjectController', [
	'only' => ['index', 'show', 'update', 'store', 'destroy']
] );

/*
| Catalog routes
*/
Route::resource( 'catalogs', 'CatalogController', [
	'only' => ['index', 'show', 'update', 'store', 'destroy']
] );
