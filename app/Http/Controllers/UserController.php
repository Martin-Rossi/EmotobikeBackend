<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class UserController extends Controller {

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

    public function follows( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        return $response->result( $user->follows );
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
