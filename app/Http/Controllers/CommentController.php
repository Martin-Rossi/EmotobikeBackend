<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class CommentController extends Controller {

    public function show( $id, ApiResponse $response ) {
        $comment = Comment::where( 'id', '=', $id )
                          ->with( 'author' )
                          ->get();

        if ( is_null( $comment ) )
            abort( 404 );

        return $response->result( $comment );
    }

    public function update( $id, Request $request, ApiResponse $response ) {
        $comment = Comment::where( 'id', '=', $id )
                          ->first();

        if ( is_null( $comment ) )
            abort( 404 );

        if ( ! $this->canTouch( $comment ) )
            abort( 403 );

        $inputs = $request->all();

        $inputs['author'] = auth()->user()->id;

        unset( $inputs['foreign_id'] );
        unset( $inputs['foreign_type'] );

        try {
            $comment->update( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Comment updated successfully' );
    }

    public function destroy( $id, ApiResponse $response ) {
        if ( auth()->user()->group_id > 2 )
            abort( 403 );

        $comment = Comment::find( $id );

        if ( is_null( $comment ) )
            abort( 404 );

        $comment->delete();

        return $response->success( 'Comment deleted successfully' );
    }

    private function canTouch( $comment ) {
        if ( auth()->user()->group_id <= 2 )
            return true;

        if ( $comment->author == auth()->user()->id )
            return true;

        return false;
    }
    
}
