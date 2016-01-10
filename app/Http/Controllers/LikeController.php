<?php

namespace App\Http\Controllers;

use App\Like;
use App\Object;
use App\Catalog;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class LikeController extends Controller {

    public function destroy( $id, ApiResponse $response ) {
        $like = Like::where( 'id', '=', $id )
                    ->first();

        if ( is_null( $like ) )
            abort( 404 );

        if ( ! $this->canTouch( $like ) )
            abort( 403 );

        try {
            $like->delete();
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        if ( 'catalog' == $like->foreign_type )
            $obj = Catalog::find( $like->foreign_id );
        else
            $obj = Object::find( $like->foreign_id );

        if ( $obj ) {
            $obj->count_likes--;
            $obj->save();
        }

        return $response->success( 'Like deleted successfully' );
    }

    private function canTouch( $like ) {
        if ( auth()->user()->group_id <= 2 )
            return true;

        if ( $like->author == auth()->user()->id )
            return true;

        return false;
    }
    
}
