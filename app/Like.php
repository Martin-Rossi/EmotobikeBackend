<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model {

    protected $table = 'likes';

    protected $fillable = [
        'foreign_id',
        'foreign_type',
        'author'
    ];

    public function author() {
    	return $this->belongsTo( 'App\User', 'author', 'id' );
    }
    
}
