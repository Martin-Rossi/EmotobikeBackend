<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

    protected $table = 'comments';

    protected $fillable = [
        'foreign_id',
        'foreign_type',
        'text',
        'author'
    ];

    public function author() {
        return $this->belongsTo( 'App\User', 'author', 'id' );
    }
}
