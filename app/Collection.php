<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model {

    protected $table = 'collections';

    protected $fillable = [
        'collection_id',
        'catalog_id',
        'author',
        'title'
    ];

}
