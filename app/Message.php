<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {

    protected $table = 'messages';

    protected $fillable = [
        'type_id',
        'message_thread',
        'message_thread_id',
        'sender',
        'recipient',
        'message',
        'image',
        'actstem'
    ];

    public function sender() {
        return $this->belongsTo( 'App\User', 'sender', 'id' );
    }

    public function recipient() {
        return $this->belongsTo( 'App\User', 'recipient', 'id' );
    }

}
