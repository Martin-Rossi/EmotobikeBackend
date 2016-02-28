<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Route extends Model {

    protected $table = 'routes';

    protected $fillable = [
        'name',
        'description',
        'data',
        'object_ids',
        'catalog_id',
        'author'
    ];

    public function catalog() {
    	return $this->belongsTo( 'App\Catalog', 'id', 'catalog_id' );
    }

}
