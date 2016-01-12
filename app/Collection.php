<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model {

    protected $table = 'collections';

    protected $fillable = [
        'collection_id',
        'foreign_id',
        'foreign_type',
        'name',
        'author'
    ];

}
