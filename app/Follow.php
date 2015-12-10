<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model {

    protected $table = 'follows';

    protected $fillable = [
        'foreign_id',
        'foreign_type',
        'author'
    ];

    public function author() {
    	return $this->belongsTo( 'App\User', 'author', 'id' );
    }
    
}
