<?php

namespace App\Http\Controllers;

use App\Catalog;
use App\Comment;
use App\Like;
use App\Follow;
use App\Type;
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
        $catalog = Catalog::where( 'id', '=', $id )
                          ->with( 'type' )
                          ->with( 'author' )
                          ->first();

        if ( is_null( $catalog ) )
            abort( 404 );

        return $response->result( $catalog );
    }

    public function store( Request $request, ApiResponse $response ) {
        $inputs = $request->all();

        $inputs['author'] = auth()->user()->id;

        if ( isset( $inputs['type'] ) && $inputs['type'] ) {
            if ( is_numeric( $inputs['type'] ) )
                $inputs['type_id'] = $inputs['type'];
            else {
                $type = Type::where( 'name', '=', $inputs['type'] )->first();

                if ( $type )
                    $inputs['type_id'] = $type->id;
                else {
                    $type = new Type();
                    $type->name = $inputs['type'];

                    $type->save();

                    $inputs['type_id'] = $type->id;
                }
            }
        }

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

        $inputs['author'] = auth()->user()->id;

        if ( isset( $inputs['type'] ) && $inputs['type'] ) {
            if ( is_numeric( $inputs['type'] ) )
                $inputs['type_id'] = $inputs['type'];
            else {
                $type = Type::where( 'name', '=', $inputs['type'] )->first();

                if ( $type )
                    $inputs['type_id'] = $type->id;
                else {
                    $type = new Type();
                    $type->name = $inputs['type'];

                    $type->save();

                    $inputs['type_id'] = $type->id;
                }
            }
        }

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

    public function contents( $id, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        return $response->result( $catalog, $catalog->objects );
    }

    public function comment( $id, Request $request, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        $comment = [
            'foreign_id'    => $id,
            'foreign_type'  => 'catalog',
            'text'          => $request->get( 'text' ),
            'author'        => auth()->user()->id
        ];

        try {
            Comment::create( $comment );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        $catalog->count_comments++;
        $catalog->save();

        return $response->success( 'Comment recorded successfully' );
    }

    public function comments( $id, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        return $response->result( $catalog->comments() );
    }

    public function like( $id, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        $like = [
            'foreign_id'    => $id,
            'foreign_type'  => 'catalog',
            'author'        => auth()->user()->id
        ];

        try {
            Like::create( $like );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        $catalog->count_likes++;
        $catalog->save();

        return $response->success( 'Like recorded successfully' );
    }

    public function likes( $id, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        return $response->result( $catalog->likes() );
    }

    public function follow( $id, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        $follow = [
            'foreign_id'    => $id,
            'foreign_type'  => 'catalog',
            'author'        => auth()->user()->id
        ];

        try {
            Follow::create( $follow );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        $catalog->count_follows++;
        $catalog->save();

        return $response->success( 'Follow recorded successfully' );
    }

    public function follows( $id, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        return $response->result( $catalog->follows() );
    }
    
}
