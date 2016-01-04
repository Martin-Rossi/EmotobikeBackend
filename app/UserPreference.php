<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model {

    protected $table = 'user_preferences';

    protected $fillable = [
        'user_id',
        'preference',
        'value'
    ];
    
    public function user() {
    	return $this->belongsTo( 'App\User', 'user_id' );
    }

}
