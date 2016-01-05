<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model {

    protected $table = 'feedbacks';

    protected $fillable = [
        'foreign_id',
        'foreign_type',
        'product_id',
        'offer_id',
        'shopper_id',
        'activity_id',
        'interface_id',
        'event',
        'channel',
        'channel_id',
        'date',
        'time',
        'taxonomy',
        'behavior',
        'behavior_frequency',
        'artifact_id',
        'artifact_frequency',
        'interaction_id',
        'interaction_frequency',
        'value',
        'author'
    ];

    public function author() {
        return $this->belongsTo( 'App\User', 'author', 'id' );
    }
}
