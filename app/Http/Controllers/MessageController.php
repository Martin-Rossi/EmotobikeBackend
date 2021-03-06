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

    public function destroy( $id, ApiResponse $response ) {
        $message = Message::find( $id );

        if ( is_null( $message ) )
            abort( 404 );

        if ( ! $this->canTouch( $message ) )
            abort( 403 );

        $message->delete();

        return $response->success( 'Message deleted successfully' );
    }

    public function messages_from_follows( ApiResponse $response ) {
        $messages = [];
        $users = [];

        $follows = auth()->user()->following;

        if ( ! sizeof( $follows ) > 0 )
            return $response->result( $messages );

        foreach ( $follows as $follow ) {
            if ( 'user' == $follow->foreign_type )
                $users[] = $follow->foreign_id;
        }

        if ( ! sizeof( $users ) > 0 )
            return $response->result( $messages );

        $messages = Message::whereIn( 'sender', $users )
                           ->with( 'sender' )
                           ->get();

        return $response->result( $messages );
    }

    public function message_group( $id, Request $request, APIResponse $response ) {
        if ( auth()->user()->group_id > 2 )
            abort( 403 );

        $users = User::where( 'group_id', '=', $id )->get();

        if ( ! sizeof( $users ) > 0 )
            return $response->error( 'No users where found in this group' );

        foreach ( $users as $user ) {
            $inputs = $request->all();

            $inputs['sender'] = auth()->user()->id;
            $inputs['recipient'] = $user->id;

            $inputs = $this->assignType( $inputs );

            try {
                Message::create( $inputs );
            } catch ( Exception $e ) {
                continue;
            }
        }

        return $response->success( 'Message sent to group successfully' );
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

    private function canTouch( $message ) {
        if ( auth()->user()->group_id <= 2 )
            return true;

        if ( $message->recipient == auth()->user()->id )
            return true;

        return false;
    }
    
}
