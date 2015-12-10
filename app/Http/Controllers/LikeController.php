<?php

namespace App\Http\Controllers;

use App\Like;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class LikeController extends Controller {

    public function destroy( $id, ApiResponse $response ) {
        $like = Like::where( 'id', '=', $id )
                    ->where( 'author', '=', auth()->user()->id )
                    ->first();

        if ( is_null( $like ) )
            abort( 404 );

        try {
            $like->delete();
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Like deleted successfully' );
    }
    
}
