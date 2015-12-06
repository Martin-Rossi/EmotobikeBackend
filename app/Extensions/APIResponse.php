<?php

namespace App\Extensions;

class APIResponse {

    protected $token;
    
    public function __construct() {
        $this->token = csrf_token();
    }

    public function error( $message = '' ) {
        return response()->json(
            [
                'type'      => 'error',
                'message'   => $message,
                '_token'    => $this->token
            ]
        );
    }

    public function success( $message = '' ) {
        return response()->json(
            [
                'type'      => 'success', 
                'message'   => $message,
                '_token'    => $this->token
            ]
        );
    }

    public function result( $content ) {
        return response()->json(
            [
                'type'      => 'result',
                'content'   => $content,
                '_token'    => $this->token
            ]
        );
    }

}
