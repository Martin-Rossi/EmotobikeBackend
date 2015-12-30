<?php

namespace App\Http\Controllers;

use App\User;
use App\Follow;
use App\Friend;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class UserController extends Controller {

    protected $pp = 10;

    public function __construct( Request $request ) {
        if ( $request->get( 'pp' ) )
            $this->pp = intval( $request->get( 'pp' ) );
    }

    public function index( ApiResponse $response ) {
        $users = User::paginate( $this->pp );

        return $response->result( $users->toArray() );
    }

    public function show( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        return $response->result( $user );
    }

    public function update( $id, Request $request, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        if ( $user->id != auth()->user()->id )
            abort( 401 );

        $inputs = $request->all();

        try {
            $user->update( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'User updated successfully' );
    }

    public function search( Request $request, ApiResponse $response ) {
        $users = User::where( 'tags', 'LIKE', '%' . $request->get( 'term' ) . '%' )
                     ->orWhere( 'name', 'LIKE', '%' . $request->get( 'term' ) . '%' )
                     ->get();

        return $response->result( $users );
    }

    public function getByField( Request $request, ApiResponse $response ) {

        $users = User::where( $request->get( 'field' ), '=',  $request->get( 'term' ) )->get();

        return $response->result( $users );
    }

    public function filter( Request $request, ApiResponse $response ) {
        $users = [];

        $operators = [
            '=',
            '<',
            '>'
        ];

        $operator = $request->get( 'operator' );

        if ( ! in_array( $operator, $operators ) )
            return $response->result( $users );

        $filters = [
            'created_at',
            'updated_at'
        ];

        $filter = $request->get( 'filter' );

        if ( ! in_array( $filter, $filters ) )
            return $response->result( $users );

        $users = User::where( $filter, $operator, $request->get( 'value' ) )
                     ->get();

        return $response->result( $users );
    }

    public function objects( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        return $response->result( $user->objects );
    }

    public function catalogs( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        return $response->result( $user->catalogs );
    }

    public function collections( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        return $response->result( $user->collections );
    }

    public function comments( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        return $response->result( $user->comments );
    }

    public function likes( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        return $response->result( $user->likes );
    }

    public function following( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        return $response->result( $user->following );
    }

    public function follow( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        $exists = Follow::where( 'foreign_id', '=', $id )
                        ->where( 'foreign_type', '=', 'user' )
                        ->where( 'author', '=', auth()->user()->id )
                        ->first();

        if ( $exists )
            return $response->error( 'This user is already followed by the authenticated user' );

        $follow = [
            'foreign_id'    => $id,
            'foreign_type'  => 'user',
            'author'        => auth()->user()->id
        ];

        try {
            Follow::create( $follow );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        $user->count_follows++;
        $user->save();

        return $response->success( 'Follow recorded successfully' );
    }

    public function follows( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        return $response->result( $user->follows() );
    }

    public function friend( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        if ( auth()->user()->id == $user->id )
            return $response->error( 'A user cannot befriend himself' );

        $inputs = [
            'from_id'       => auth()->user()->id,
            'from_accepted' => 1,
            'to_id'         => $user->id,
            'to_accepted'   => 0
        ];

        try {
            Friend::create( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'User successfully added as a friend' );
    }

    public function unfriend( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        if ( auth()->user()->id == $user->id )
            return $response->error( 'A user cannot unfriend himself' );

        $friendship = Friend::where( 'from_id', '=', auth()->user()->id )
                            ->where( 'to_id', '=', $user->id )
                            ->first();

        if ( is_null( $friendship ) )
            $friendship = Friend::where( 'to_id', '=', auth()->user()->id )
                                ->where( 'from_id', '=', $user->id )
                                ->first();

        if ( is_null( $friendship ) )
            return $response->error( 'Friendship does not exists, nothing to do here' );

        $friendship->delete();

        return $response->success( 'User successfully unfriended' );
    }

    public function friends( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        $friend_ids = [];

        $frs = \App\Friend::where( 'from_id', '=', $user->id )
                          ->orWhere( 'to_id', '=', $user->id )
                          ->get();

        if ( ! sizeof( $frs ) > 0 )
            return $response->result( $friend_ids );

        foreach ( $frs as $fr ) {
            if ( ! $fr->from_accepted )
                continue;

            if ( ! $fr->to_accepted )
                continue;

            if ( $fr->from_id == $user->id )
                $friend_ids[] = $fr->to_id;
            else
                $friend_ids[] = $fr->from_id;
        }

        $friends = User::whereIn( 'id', $friend_ids )->paginate( $this->pp );

        return $response->result( $friends->toArray() );
    }

    public function feedbacks( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        return $response->result( $user->feedbacks );
    }

    public function messages_sent( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        return $response->result( $user->outbox );
    }
    
    public function messages_received( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        return $response->result( $user->inbox );
    }

}
