<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model {

    protected $table = 'friends';

    protected $fillable = [
        'from_id',
        'from_accepted',
        'to_id',
        'to_accepted'
    ];

    public function from() {
    	return $this->hasOne( 'App\User', 'id', 'from_id' );
    }

    public function to() {
    	return $this->hasOne( 'App\User', 'id', 'to_id' );
    }

}
