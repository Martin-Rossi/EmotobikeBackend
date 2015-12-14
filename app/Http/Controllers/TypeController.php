<?php

namespace App\Http\Controllers;

use App\Type;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class TypeController extends Controller {

    public function index( ApiResponse $response ) {
        $types = Type::all();

        return $response->result( $types );
    }

    public function show( $id, ApiResponse $response ) {
        $type = Type::find( $id );

        if ( is_null( $type ) )
            abort( 404 );

        return $response->result( $type );
    }

    public function objects( $id, ApiResponse $response ) {
        $type = Type::find( $id );

        if ( is_null( $type ) )
            abort( 404 );

        return $response->result( $type->objects );
    }

    public function catalogs( $id, ApiResponse $response ) {
        $type = Type::find( $id );

        if ( is_null( $type ) )
            abort( 404 );

        return $response->result( $type->catalogs );
    }
    
}
