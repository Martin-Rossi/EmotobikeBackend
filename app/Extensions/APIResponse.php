<?php

namespace App\Extensions;

class APIResponse {

    protected $token;
    protected $user_id;
    
    public function __construct() {
        $this->token = csrf_token();

        if ( auth()->user() )
            $this->user_id = auth()->user()->id;
    }

    public function error( $message = '' ) {
        return response()->json(
            [
                'type'      => 'error',
                'message'   => $message,
                '_user_id'  => $this->user_id,
                '_token'    => $this->token
            ]
        );
    }

    public function success( $message = '' ) {
        return response()->json(
            [
                'type'      => 'success', 
                'message'   => $message,
                '_user_id'  => $this->user_id,
                '_token'    => $this->token
            ]
        );
    }

    public function result( $content ) {
        return response()->json(
            [
                'type'      => 'result',
                'content'   => $content,
                '_user_id'  => $this->user_id,
                '_token'    => $this->token
            ]
        );
    }

}
