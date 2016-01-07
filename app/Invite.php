<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model {

    protected $table = 'invites';

    protected $fillable = [
        'email',
        'accepted',
        'accepted_on',
        'author'
    ];

    public function author() {
        return $this->belongsTo( 'App\User', 'author', 'id' );
    }

}
