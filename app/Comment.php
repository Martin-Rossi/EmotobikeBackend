<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

    protected $table = 'comments';

    protected $fillable = [
        'object_id',
        'text',
        'author'
    ];

    public function object() {
    	return $this->belongsTo( 'App\Object', 'object_id', 'id' );
    }

    public function user() {
        return $this->belongsTo( 'App\User', 'author', 'id' );
    }
}
