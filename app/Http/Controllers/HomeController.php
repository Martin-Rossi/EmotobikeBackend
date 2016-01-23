<?php

namespace App\Http\Controllers;

use App\User;
use App\Friend;
use App\Follow;
use App\Object;
use App\Catalog;
use App\Collection;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class HomeController extends Controller {

    protected $pp = 10;

    public function __construct( Request $request ) {
        if ( $request->get( 'pp' ) )
            $this->pp = intval( $request->get( 'pp' ) );
    }

    public function index( ApiResponse $response ) {
        $catalog_ids = [];

        // Check to see if there are any objects in a Personalization Collection.  If so, retrieve the tags of the objects in the collection, and search for catalogs containing these tags.
        $pcs = Collection::where( 'foreign_type', '=', 'object' )
                         ->where( 'name', '=', 'Personalization' )
                         ->where( 'author', '=', auth()->user()->id )
                         ->where( 'status', '>', 0 )
                         ->get();

        if ( sizeof( $pcs ) ) {
            foreach ( $pcs as $pc ) {
                $po = Object::find( $pc->foreign_id );

                if ( is_null( $po ) )
                    continue;

                $tags = explode( ';', $po->tags );

                if ( ! sizeof( $tags ) > 0 )
                    continue;

                foreach ( $tags as $tag ) {
                    $pocs = Catalog::where( 'tags', 'LIKE', '%' . $tag . '%' )
                                   ->where( 'status', '>', 0 )
                                   ->get();

                    if ( sizeof( $pocs ) )
                        foreach ( $pocs as $poc )
                            $catalog_ids[] = $poc->id;
                }
            }
        }

        // Check to see if there are any catalogs for the user in which the tag 'recommended' is 1. Return those catalogs
        $rcs = Catalog::where( 'author', '=', auth()->user()->id )
                      ->where( 'count_recommended', '>', 0 )
                      ->where( 'status', '>', 0 )
                      ->get();

        if ( sizeof( $rcs ) > 0 )
            foreach ( $rcs as $rc )
                $catalog_ids[] = $rc->id;

        // Check to see if there are any catalogs from friends. Return those catalogs
        $friend_ids = [];

        $frs = Friend::where( 'from_id', '=', auth()->user()->id )
                     ->orWhere( 'to_id', '=', auth()->user()->id )
                     ->get();

        if ( sizeof( $frs ) > 0 ) {
            foreach ( $frs as $fr ) {
                if ( ! $fr->from_accepted )
                    continue;

                if ( ! $fr->to_accepted )
                    continue;

                if ( $fr->from_id == auth()->user()->id )
                    $friend_ids[] = $fr->to_id;
                else
                    $friend_ids[] = $fr->from_id;
            }

            $fcs = Catalog::whereIn( 'author', $friend_ids )
                          ->where( 'status', '>', 0 )
                          ->get();

            if ( sizeof( $fcs ) )
                foreach ( $fcs as $fc )
                    $catalog_ids[] = $fc->id;
        }

        // Check to see if there are any catalogs from people being followed. Return those catalogs
        $follow_ids = [];

        $follows = Follow::where( 'author', '=', auth()->user()->id )
                         ->where( 'foreign_type', '=', 'user' )
                         ->get();

        if ( sizeof( $follows ) > 0 ) {
            foreach ( $follows as $follow )
                $follow_ids[] = $follow->foreign_id;

            $focs = Catalog::whereIn( 'author', $follow_ids )
                           ->where( 'status', '>', 0 )
                           ->get();

            if ( sizeof( $focs ) > 0 )
                foreach ( $focs as $foc )
                    $catalog_ids[] = $foc->id;
        }

        $catalogs = Catalog::whereIn( 'id', $catalog_ids )
                           ->paginate( $this->pp );

        return $response->result( $catalogs->toArray() );
    }
    
}
