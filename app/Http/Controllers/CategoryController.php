<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class CategoryController extends Controller {

    public function index( ApiResponse $response ) {
        $categories = Category::all();

        return $response->result( $categories );
    }

    public function show( $id, ApiResponse $response ) {
        $category = Category::find( $id );

        if ( is_null( $category ) )
            abort( 404 );

        return $response->result( $category );
    }

    public function objects( $id, ApiResponse $response ) {
        $category = Category::find( $id );

        if ( is_null( $category ) )
            abort( 404 );

        return $response->result( $category->objects );
    }

    public function catalogs( $id, ApiResponse $response ) {
        $category = Category::find( $id );

        if ( is_null( $category ) )
            abort( 404 );

        return $response->result( $category->catalogs );
    }
    
}
