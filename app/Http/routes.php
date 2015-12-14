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
Route::post( 'auth/login', ['middleware' => 'cors', 'uses' =>'Auth\AuthController@postLogin'] );
Route::get( 'auth/login', ['middleware' => 'cors', 'uses' => 'Auth\AuthController@getLogin'] );
Route::get( 'auth/logout', ['middleware' => 'cors', 'uses' => 'Auth\AuthController@getLogout'] );

/*
| Object routes
*/
Route::group( ['middleware' => ['auth', 'cors']], function () {
	Route::resource( 'objects', 'ObjectController', [
		'only' => ['index', 'show', 'update', 'store', 'destroy']
	] );

	Route::get( 'objects/{id}/catalog', 'ObjectController@catalog' );
	Route::get( 'objects/{id}/comments', 'ObjectController@comments' );
	Route::get( 'objects/{id}/likes', 'ObjectController@likes' );
	Route::get( 'objects/{id}/follows', 'ObjectController@follows' );

	Route::post( 'objects/{id}/comment', 'ObjectController@comment' );
	Route::post( 'objects/{id}/like', 'ObjectController@like' );
	Route::post( 'objects/{id}/follow', 'ObjectController@follow' );
} );

/*
| Catalog routes
*/
Route::group( ['middleware' => ['auth', 'cors']], function () {
	Route::resource( 'catalogs', 'CatalogController', [
		'only' => ['index', 'show', 'update', 'store', 'destroy']
	] );
	
	Route::get( 'catalogs/{id}/objects', 'CatalogController@objects' );
	Route::get( 'catalogs/{id}/content', 'CatalogController@contents' );
	Route::get( 'catalogs/{id}/comments', 'CatalogController@comments' );
	Route::get( 'catalogs/{id}/likes', 'CatalogController@likes' );
	Route::get( 'catalogs/{id}/follows', 'CatalogController@follows' );

	Route::post( 'catalogs/{id}/comment', 'CatalogController@comment' );
	Route::post( 'catalogs/{id}/like', 'CatalogController@like' );
	Route::post( 'catalogs/{id}/follow', 'CatalogController@follow' );
} );

/*
| Type routes
*/
Route::group( ['middleware' => ['auth', 'cors']], function () {
	Route::resource( 'types', 'TypeController', [
		'only' => ['index', 'show']
	] );
	
	Route::get( 'types/{id}/objects', 'TypeController@objects' );
	Route::get( 'types/{id}/catalogs', 'TypeController@catalogs' );
} );

/*
| Comment routes
*/
Route::group( ['middleware' => ['auth', 'cors']], function () {
	Route::resource( 'comments', 'CommentController', [
		'only' => ['show', 'update']
	] );
} );

/*
| Like routes
*/
Route::group( ['middleware' => ['auth', 'cors']], function () {
	Route::resource( 'likes', 'LikeController', [
		'only' => ['destroy']
	] );
} );

/*
| Follow routes
*/
Route::group( ['middleware' => ['auth', 'cors']], function () {
	Route::resource( 'follows', 'FollowController', [
		'only' => ['destroy']
	] );
} );