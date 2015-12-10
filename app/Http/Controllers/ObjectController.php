<?php

namespace App\Http\Controllers;

use App\Object;
use App\Comment;
use App\Like;
use App\Follow;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class ObjectController extends Controller {

    public function index( ApiResponse $response ) {
        $objects = Object::all();

        return $response->result( $objects );
    }

    public function show( $id, ApiResponse $response ) {
        $object = Object::find( $id );

        if ( is_null( $object ) )
            abort( 404 );

        return $response->result( $object );
    }

    public function store( Request $request, ApiResponse $response ) {
        $inputs = $request->all();

        $inputs['author'] = auth()->user()->id;

        try {
            Object::create( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Object created successfully' );
    }

    public function update( $id, Request $request, ApiResponse $response ) {
        $object = Object::where( 'id', '=', $id )
                        ->where( 'author', '=', auth()->user()->id )
                        ->first();

        if ( is_null( $object ) )
            abort( 404 );

        $inputs = $request->all();

        $inputs['author'] = auth()->user()->id;

        try {
            $object->update( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Object updated successfully' );
    }

    public function catalog( $id, ApiResponse $response ) {
        $object = Object::find( $id );

        if ( is_null( $object ) )
            abort( 404 );

        return $response->result( $object->catalog );
    }

    public function comment( $id, Request $request, ApiResponse $response ) {
        $object = Object::find( $id );

        if ( is_null( $object ) )
            abort( 404 );

        $comment = [
            'foreign_id'    => $id,
            'foreign_type'  => 'object',
            'text'          => $request->get( 'text' ),
            'author'        => auth()->user()->id
        ];

        try {
            Comment::create( $comment );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Comment recorded successfully' );
    }

    public function comments( $id, ApiResponse $response ) {
        $object = Object::find( $id );

        if ( is_null( $object ) )
            abort( 404 );

        return $response->result( $object->comments() );
    }

    public function like( $id, ApiResponse $response ) {
        $object = Object::find( $id );

        if ( is_null( $object ) )
            abort( 404 );

        $like = [
            'foreign_id'    => $id,
            'foreign_type'  => 'object',
            'author'        => auth()->user()->id
        ];

        try {
            Like::create( $like );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Like recorded successfully' );
    }

    public function likes( $id, ApiResponse $response ) {
        $object = Object::find( $id );

        if ( is_null( $object ) )
            abort( 404 );

        return $response->result( $object->likes() );
    }

    public function follow( $id, ApiResponse $response ) {
        $object = Object::find( $id );

        if ( is_null( $object ) )
            abort( 404 );

        $follow = [
            'foreign_id'    => $id,
            'foreign_type'  => 'object',
            'author'        => auth()->user()->id
        ];

        try {
            Follow::create( $follow );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Follow recorded successfully' );
    }

    public function follows( $id, ApiResponse $response ) {
        $object = Object::find( $id );

        if ( is_null( $object ) )
            abort( 404 );

        return $response->result( $object->follows() );
    }
    
}
