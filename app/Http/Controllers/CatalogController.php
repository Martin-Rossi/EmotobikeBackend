<?php

namespace App\Http\Controllers;

use App\Catalog;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class CatalogController extends Controller {

    public function index( ApiResponse $response ) {
        $catalogs = Catalog::all();

        return $response->result( $catalogs );
    }

    public function show( $id, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        return $response->result( $catalog );
    }

    public function store( Request $request, ApiResponse $response ) {
        $inputs = $request->all();

        $inputs['author'] = auth()->user()->id;

        try {
            Catalog::create( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Catalog created successfully' );
    }

    public function update( $id, Request $request, ApiResponse $response ) {
        $catalog = Catalog::where( 'id', '=', $id )
                          ->where( 'author', '=', auth()->user()->id )
                          ->first();

        if ( is_null( $catalog ) )
            abort( 404 );

        $inputs = $request->all();

        try {
            $catalog->update( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Catalog updated successfully' );
    }

    public function objects( $id, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        return $response->result( $catalog->objects );
    }
    
}
