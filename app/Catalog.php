<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model {

    protected $table = 'catalogs';

    protected $fillable = [
        'collection_id',
        'name',
        'title',
        'author'
    ];

    public function objects() {
    	return $this->hasMany( 'App\Object', 'catalog_id', 'id' );
    }
}
