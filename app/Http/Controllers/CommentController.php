<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class CommentController extends Controller {

    public function store( Request $request, ApiResponse $response ) {
        $inputs = $request->all();

        $inputs['author'] = auth()->user()->id;

        try {
            Comment::create( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Comment added successfully' );
    }

    public function update( $id, Request $request, ApiResponse $response ) {
        $comment = Comment::where( 'id', '=', $id )
                          ->where( 'author', '=', auth()->user()->id )
                          ->first();

        if ( is_null( $comment ) )
            abort( 404 );

        $inputs = $request->all();

        $inputs['author'] = auth()->user()->id;

        unset( $inputs['object_id'] );

        try {
            $comment->update( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Comment updated successfully' );
    }
    
}
