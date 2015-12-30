<?php

namespace App\Http\Controllers;

use App\Friend;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class FriendController extends Controller {

    public function requests( ApiResponse $response ) {
        $requests = Friend::where( 'to_id', '=', auth()->user()->id )
                          ->where( 'to_accepted', '=', 0 )
                          ->with( 'from' )
                          ->get();

        return $response->result( $requests );
    }

    public function accept( $id, ApiResponse $response ) {
        $friendship = Friend::find( $id );

        if ( is_null( $friendship ) )
            abort( 404 );

        if ( auth()->user()->id != $friendship->to_id )
            abort( 404 );

        $friendship->to_accepted = 1;
        $friendship->save();

        return $response->success( 'Friendship accepted successfully' );
    }
    
}
