<?php

namespace App\Http\Controllers;

use App\Message;
use App\User;
use App\Type;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class MessageController extends Controller {

    public function show( $id, ApiResponse $response ) {
        $message = Message::where( 'id', '=', $id )
                          ->with( 'sender' )
                          ->with( 'recipient' )
                          ->first();

        if ( is_null( $message ) )
            abort( 404 );

        return $response->result( $message );
    }

    public function store( Request $request, ApiResponse $response ) {
        $inputs = $request->all();

        $inputs['sender'] = auth()->user()->id;

        $inputs = $this->assignType( $inputs );

        try {
            Message::create( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'New message added successfully' );
    }

    public function reply( $id, Request $request, ApiResponse $response ) {
        $message = Message::find( $id );

        if ( is_null( $message ) )
            abort( 404 );

        $message->count_replies++;
        $message->save();

        $inputs = $request->all();

        $inputs['sender'] = auth()->user()->id;
        $inputs['recipient'] = $message->sender;
        $inputs['message_thread'] = $message->message_thread + 1;
        $inputs['message_thread_id'] = $message->id;

        $inputs = $this->assignType( $inputs );

        try {
            Message::create( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'New message added successfully' );
    }

    private function assignType( $inputs ) {
        if ( isset( $inputs['type'] ) && $inputs['type'] ) {
            if ( is_numeric( $inputs['type'] ) )
                $inputs['type_id'] = $inputs['type'];
            else {
                $type = Type::where( 'name', '=', $inputs['type'] )->first();

                if ( $type )
                    $inputs['type_id'] = $type->id;
                else {
                    $type = new Type();
                    $type->name = $inputs['type'];

                    $type->save();

                    $inputs['type_id'] = $type->id;
                }
            }
        }

        return $inputs;
    }
    
}
