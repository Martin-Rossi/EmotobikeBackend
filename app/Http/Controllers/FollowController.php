<?php

namespace App\Http\Controllers;

use App\Follow;
use App\Object;
use App\Catalog;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class FollowController extends Controller {

    public function destroy( $id, ApiResponse $response ) {
        $follow = Follow::where( 'id', '=', $id )
                        ->first();

        if ( is_null( $follow ) )
            abort( 404 );

        if ( ! $this->canTouch( $follow ) )
            abort( 403 );

        try {
            $follow->delete();
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        if ( 'catalog' == $follow->foreign_type )
            $obj = Catalog::find( $follow->foreign_id );
        else
            $obj = Object::find( $follow->foreign_id );

        if ( $obj ) {
            $obj->count_follows--;
            $obj->save();
        }

        return $response->success( 'Follow deleted successfully' );
    }

    private function canTouch( $follow ) {
        if ( auth()->user()->group_id <= 2 )
            return true;

        if ( $follow->author == auth()->user()->id )
            return true;

        return false;
    }
    
}
