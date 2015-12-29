<?php

namespace App\Http\Controllers;

use App\Route;
use App\Object;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class RouteController extends Controller {

    protected $pp = 10;

    public function __construct( Request $request ) {
        if ( $request->get( 'pp' ) )
            $this->pp = intval( $request->get( 'pp' ) );
    }

    public function index( ApiResponse $response ) {
        $routes = Route::where( 'status', '>', 0 )
                       ->where( 'author', '=', auth()->user()->id )
                       ->paginate( $this->pp );

        return $response->result( $routes->toArray() );
    }

    public function show( $id, ApiResponse $response ) {
        $route = Route::where( 'id', '=', $id )
                      ->where( 'author', '=', auth()->user()->id )
                      ->first();

        return $response->result( $route );
    }

    public function store( Request $request, ApiResponse $response ) {
        $inputs = $request->all();

        $inputs['author'] = auth()->user()->id;

        try {
            Route::create( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Route created successfully' );
    }

    public function update( $id, Request $request, ApiResponse $response ) {
        $route = Route::where( 'id', '=', $id )
                      ->where( 'author', '=', auth()->user()->id )
                      ->first();

        if ( is_null( $route ) )
            abort( 404 );

        $inputs = $request->all();

        $inputs['author'] = auth()->user()->id;

        try {
            $route->update( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Route updated successfully' );
    }

    public function destroy( $id, ApiResponse $response ) {
        $route = Route::where( 'id', '=', $id )
                      ->where( 'author', '=', auth()->user()->id )
                      ->first();

        if ( is_null( $route ) )
            abort( 404 );

        $route->status = -1;
        $route->save();

        return $response->success( 'Route succesfully deleted' );
    }

    public function deleted( ApiResponse $response ) {
        $routes = Route::where( 'status', '<', 0 )
                       ->where( 'author', '=', auth()->user()->id )
                       ->paginate( $this->pp );

        return $response->result( $routes->toArray() );
    }

    public function objects( $id, ApiResponse $response ) {
        $route = Route::where( 'id', '=', $id )
                      ->where( 'author', '=', auth()->user()->id )
                      ->first();

        $object_ids = explode( ';', $route->object_ids );

        if ( ! sizeof( $object_ids ) > 0 )
            return $response->result( $object_ids );

        $objects = Object::whereIn( 'id', $object_ids )
                         ->get();

        return $response->result( $objects );
    }
    
}
