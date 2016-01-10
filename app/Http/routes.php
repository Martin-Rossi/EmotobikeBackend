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
Route::post( 'auth/registration', ['middleware' => 'cors', 'uses' =>'Auth\AuthController@postRegistration'] );
Route::get( 'auth/login', ['middleware' => 'cors', 'uses' => 'Auth\AuthController@getLogin'] );
Route::get( 'auth/logout', ['middleware' => 'cors', 'uses' => 'Auth\AuthController@getLogout'] );

/*
| User routes
*/
Route::group( ['middleware' => ['auth', 'cors']], function () {
	Route::resource( 'users', 'UserController', [
		'only' => ['index', 'show', 'update', 'store']
	] );

	Route::get( 'users/{id}/objects', 'UserController@objects' );
	Route::get( 'users/{id}/catalogs', 'UserController@catalogs' );
	Route::get( 'users/{id}/collections', 'UserController@collections' );
	Route::get( 'users/{id}/comments', 'UserController@comments' );
	Route::get( 'users/{id}/likes', 'UserController@likes' );
	Route::get( 'users/{id}/following', 'UserController@following' );
	Route::get( 'users/{id}/feedbacks', 'UserController@feedbacks' );
	Route::get( 'users/{id}/follows', 'UserController@follows' );
	Route::get( 'users/{id}/friends', 'UserController@friends' );
	Route::get( 'users/{id}/messages/sent', 'UserController@messages_sent' );
	Route::get( 'users/{id}/messages/received', 'UserController@messages_received' );
	Route::get( 'users/{id}/invites/sent', 'UserController@invites_sent' );

	Route::post( 'users/{id}/follow', 'UserController@follow' );
	Route::post( 'users/{id}/friend', 'UserController@friend' );
	Route::post( 'users/{id}/unfriend', 'UserController@unfriend' );

	Route::post( 'search/users', 'UserController@search' );
	Route::post( 'field/users', 'UserController@getByField' );
	Route::post( 'filter/users', 'UserController@filter' );

	Route::get( 'users/preferences/all', 'UserController@getAllPreferences' );
	Route::get( 'users/preferences/{key}/get', 'UserController@getPreference' );
	Route::post( 'users/preferences/{key}/set', 'UserController@setPreference' );

	Route::post( 'users/{id}/commissions/rate', 'UserController@setCommissionRate' );
	Route::post( 'users/{id}/commissions/exchange', 'UserController@setCommissionExchange' );
	Route::post( 'users/{id}/commissions/pay', 'UserController@payCommission' );
} );

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
	Route::get( 'objects/{id}/recommendations', 'ObjectController@recommendations' );
	Route::get( 'objects/{id}/feedbacks', 'ObjectController@feedbacks' );

	Route::post( 'objects/{id}/comment', 'ObjectController@comment' );
	Route::post( 'objects/{id}/like', 'ObjectController@like' );
	Route::post( 'objects/{id}/follow', 'ObjectController@follow' );
	Route::post( 'objects/{id}/recommend', 'ObjectController@recommend' );
	Route::post( 'objects/{id}/feedback', 'ObjectController@feedback' );

	Route::get( 'deleted/objects', 'ObjectController@deleted' );

	Route::post( 'search/objects', 'ObjectController@search' );
	Route::post( 'filter/objects', 'ObjectController@filter' );
} );

Route::get( 'positions/{id}/objects', ['middleware' => 'cors', 'uses' => 'ObjectController@positions'] );

/*
| Catalog routes
*/
Route::group( ['middleware' => ['auth', 'cors']], function () {
	Route::resource( 'catalogs', 'CatalogController', [
		'only' => ['index', 'show', 'update', 'store', 'destroy']
	] );
	
	Route::get( 'catalogs/{id}/objects', 'CatalogController@objects' );
	Route::get( 'catalogs/{id}/products', 'CatalogController@products' );
	Route::get( 'catalogs/{id}/content', 'CatalogController@contents' );
	Route::get( 'catalogs/{id}/comments', 'CatalogController@comments' );
	Route::get( 'catalogs/{id}/likes', 'CatalogController@likes' );
	Route::get( 'catalogs/{id}/follows', 'CatalogController@follows' );
	Route::get( 'catalogs/{id}/recommendations', 'CatalogController@recommendations' );
	Route::get( 'catalogs/{id}/feedbacks', 'CatalogController@feedbacks' );
	Route::get( 'catalogs/{id}/activities', 'CatalogController@activities' );

	Route::post( 'catalogs/{id}/comment', 'CatalogController@comment' );
	Route::post( 'catalogs/{id}/like', 'CatalogController@like' );
	Route::post( 'catalogs/{id}/follow', 'CatalogController@follow' );
	Route::post( 'catalogs/{id}/recommend', 'CatalogController@recommend' );
	Route::post( 'catalogs/{id}/feedback', 'CatalogController@feedback' );

	Route::get( 'deleted/catalogs', 'CatalogController@deleted' );

	Route::post( 'search/catalogs', 'CatalogController@search' );
	Route::post( 'filter/catalogs', 'CatalogController@filter' );

} );

/*
| Collection routes
*/
Route::group( ['middleware' => ['auth', 'cors']], function () {
	Route::resource( 'collections', 'CollectionController', [
		'only' => ['index', 'show', 'store', 'destroy']
	] );

	Route::get( 'collections/{id}/catalogs', 'CollectionController@catalogs' );
	Route::get( 'collections/{id}/objects', 'CollectionController@objects' );

	Route::post( 'collections/{id}/add/object', 'CollectionController@addObject' );
	Route::post( 'collections/{id}/add/catalog', 'CollectionController@addCatalog' );
	Route::post( 'collections/{id}/remove/object', 'CollectionController@removeObject' );
	Route::post( 'collections/{id}/remove/catalog', 'CollectionController@removeCatalog' );

	Route::get( 'deleted/collections', 'CollectionController@deleted' );
} );

/*
| GenericCollection routes
*/
Route::group( ['middleware' => ['auth', 'cors']], function () {
	Route::resource( 'generic-collections', 'GenericCollectionController', [
		'only' => ['index', 'show', 'store', 'destroy']
	] );

	Route::get( 'generic-collections/{id}/catalogs', 'GenericCollectionController@catalogs' );
	Route::get( 'generic-collections/{id}/objects', 'GenericCollectionController@objects' );

	Route::post( 'generic-collections/{id}/add/object', 'GenericCollectionController@addObject' );
	Route::post( 'generic-collections/{id}/add/catalog', 'GenericCollectionController@addCatalog' );
	Route::post( 'generic-collections/{id}/remove/object', 'GenericCollectionController@removeObject' );
	Route::post( 'generic-collections/{id}/remove/catalog', 'GenericCollectionController@removeCatalog' );

	Route::get( 'deleted/generic-collections', 'GenericCollectionController@deleted' );
} );

/*
| Route routes
*/
Route::group( ['middleware' => ['auth', 'cors']], function () {
	Route::resource( 'routes', 'RouteController', [
		'only' => ['index', 'show', 'update', 'store', 'destroy']
	] );

	Route::get( 'routes/{id}/objects', 'RouteController@objects' );
	Route::get( 'deleted/routes', 'CollectionController@deleted' );
} );

/*
| Category routes
*/
Route::group( ['middleware' => ['auth', 'cors']], function () {
	Route::resource( 'categories', 'CategoryController', [
		'only' => ['index', 'show']
	] );
	
	Route::get( 'categories/{id}/objects', 'CategoryController@objects' );
	Route::get( 'categories/{id}/catalogs', 'CategoryController@catalogs' );
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
		'only' => ['show', 'update', 'destroy']
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

/*
| Friend routes
*/
Route::group( ['middleware' => ['auth', 'cors']], function() {
	Route::get( 'friends/requests', 'FriendController@requests' );
	Route::post( 'friends/{id}/accept', 'FriendController@accept' );
} );

/*
| Activity routes
*/
Route::group( ['middleware' => ['auth', 'cors']], function () {
	Route::resource( 'activities', 'ActivityController', [
		'only' => ['show', 'update', 'store']
	] );

	Route::post( 'search/activities', 'ActivityController@search' );
	Route::post( 'filter/activities', 'ActivityController@filter' );
} );

/*
| PersonalPrice routes
*/
Route::group( ['middleware' => ['auth', 'cors']], function() {
	Route::resource( 'pprices', 'PersonalPriceController', [
		'only' => ['update', 'store', 'destroy']
	] );
} );

/*
| Message routes
*/
Route::group( ['middleware' => ['auth', 'cors']], function() {
	Route::resource( 'messages', 'MessageController', [
		'only' => ['show', 'store', 'destroy']
	] );

	Route::post( 'messages/{id}/reply', 'MessageController@reply' );

	Route::get( 'messages/from/follows', 'MessageController@messages_from_follows' );
} );

/*
| Invite routes
*/
Route::group( ['middleware' => ['auth', 'cors']], function() {
	Route::resource( 'invites', 'InviteController', [
		'only' => ['store']
	] );
} );

/*
| Commission routes
*/
Route::group( ['middleware' => ['auth', 'cors']], function() {
	Route::resource( 'commissions', 'CommissionController', [
		'only' => ['index', 'show', 'store']
	] );

	Route::post( 'filter/commissions', 'CommissionController@filter' );
} );