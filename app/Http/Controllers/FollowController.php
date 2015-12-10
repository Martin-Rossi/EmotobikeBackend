<?php

namespace App\Http\Controllers;

use App\Follow;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class FollowController extends Controller {

    public function destroy( $id, ApiResponse $response ) {
        $follow = Follow::where( 'id', '=', $id )
                        ->where( 'author', '=', auth()->user()->id )
                        ->first();

        if ( is_null( $follow ) )
            abort( 404 );

        try {
            $follow->delete();
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Follow deleted successfully' );
    }
    
}
