<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model {

    protected $table = 'recommendations';

    protected $fillable = [
        'foreign_id',
        'foreign_type',
        'author'
    ];

    public function author() {
    	return $this->belongsTo( 'App\User', 'author', 'id' );
    }
    
}
