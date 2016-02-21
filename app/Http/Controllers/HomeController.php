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
        $rcs = Catalog::where( 'count_recommended', '>', 0 )
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
                           ->orderBy( 'updated_at', 'DESC' )
                           ->with( 'author' )
                           ->paginate( $this->pp );

        return $response->result( $catalogs->toArray() );
    }

    public function noteworthy( ApiResponse $response ) {
        $users_ids = [];

        $nus = User::where( 'group_id', '>=', 200 )
                   ->where( 'noteworthy', '=', 1 )
                   ->get();

        if ( sizeof( $nus ) > 0 ) {
            foreach ( $nus as $nu ) {
                $users_ids[] = $nu->id;

                $follow_ids = [];

                $follows = Follow::where( 'foreign_id', '=', $nu->id )
                                 ->where( 'foreign_type', '=', 'user' )
                                 ->get();

                if ( sizeof( $follows ) > 0 ) {
                    foreach ( $follows as $follow )
                        $follow_ids[] = $follow->author;

                    $focs = User::whereIn( 'id', $follow_ids )
                                ->get();

                    if ( sizeof( $focs ) > 0 )
                        foreach ( $focs as $foc )
                            $users_ids[] = $foc->id;
                }
            }
        }

        $users = User::whereIn( 'id', $users_ids )
                     ->with( 'catalogs' )
                     ->with( 'following' )
                     ->get();

        $i = 0;

        if ( sizeof( $users ) > 0 ) {
            foreach ( $users as $user ) {
                if ( sizeof( $user->following ) > 0 ) {
                    
                    $j = 0;

                    foreach ( $user->following as $follow ) {
                        $fu = User::find( $follow->id );

                        $users[$i]->following[$j]->user = $fu;

                        $j++;
                    }
                }

                $i++;
            }
        }

        return $response->result( $users->toArray() );
    }
    
}
