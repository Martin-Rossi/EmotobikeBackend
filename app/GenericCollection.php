<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GenericCollection extends Model {

    protected $table = 'generic_collections';

    protected $fillable = [
    	'collection_id',
        'foreign_id',
        'foreign_type'
    ];

}
