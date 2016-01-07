<?php

namespace App\Http\Controllers;

use Mail;
use Validator;
use App\Invite;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class InviteController extends Controller {

    public function store( Request $request, ApiResponse $response ) {
        $inputs = $request->all();

        $validator = Validator::make( $request->all(), [
            'email' => 'required|email|max:255'
        ] );

        if ( $validator->fails() )
            return $response->error( 'Invalid email address' );

        $user = User::where( 'email', '=', $inputs['email'] )
                    ->first();

        if ( $user )
            return $response->error( 'This user is already registered' );

        $inputs['accepted'] = 0;
        $inputs['accepted_on'] = '0000-00-00 00:00:00';
        $inputs['author'] = auth()->user()->id;

        try {
            Invite::create( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        Mail::send( 'emails.invite', ['user' => auth()->user()], function ( $mail ) use ( $inputs ) {
            $mail->to( $inputs['email'], '' )->subject( 'CatalogAPI invite' );
        } );

        return $response->success( 'Invite sent successfully' );
    }

}
