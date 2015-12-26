<?php

namespace App\Http\Controllers;

use App\PersonalPrice;
use App\User;
use App\Object;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class PersonalPriceController extends Controller {

    public function store( Request $request, ApiResponse $response ) {
        $inputs = $request->all();

        $user = User::find( $inputs['user_id'] );

        if ( is_null( $user ) )
            return $response->error( 'User not found' );

        $object = Object::find( $inputs['object_id'] );

        if ( is_null( $object ) )
            return $response->error( 'Object not found' );

        if ( $object->author != auth()->user()->id )
            return $response->error( 'Owner missmatch error' );

        try {
            PersonalPrice::create( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Personal price added successfully' );
    }

    public function update( $id, Request $request, ApiResponse $response ) {
        $pprice = PersonalPrice::find( $id );

        if ( is_null( $pprice ) )
            abort( 404 );

        $inputs = $request->all();

        $user = User::find( $inputs['user_id'] );

        if ( is_null( $user ) )
            return $response->error( 'User not found' );

        $object = Object::find( $inputs['object_id'] );

        if ( is_null( $object ) )
            return $response->error( 'Object not found' );

        if ( $object->author != auth()->user()->id )
            return $response->error( 'Owner missmatch error' );

        try {
            $pprice->update( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Personal price updated successfully' );
    }

    public function destroy( $id, ApiResponse $response ) {
        $pprice = PersonalPrice::find( $id );

        if ( is_null( $pprice ) )
            return $response->error( 'Personal price not found' );

        $user = User::find( $pprice->user_id );

        if ( is_null( $user ) )
            return $response->error( 'User not found' );

        $object = Object::find( $pprice->object_id );

        if ( is_null( $object ) )
            return $response->error( 'Object not found' );

        if ( $object->author != auth()->user()->id )
            return $response->error( 'Owner missmatch error' );

        $pprice->delete();

        return $response->success( 'Personal price succesfully deleted' );
    }
    
}
