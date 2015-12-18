<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model {

    protected $table = 'feedbacks';

    protected $fillable = [
        'foreign_id',
        'foreign_type',
        'value',
        'author'
    ];

    public function author() {
        return $this->belongsTo( 'App\User', 'author', 'id' );
    }
}
