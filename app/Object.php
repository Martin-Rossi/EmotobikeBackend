<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Object extends Model {

    protected $table = 'objects';

    protected $fillable = [
        'parent',
        'category_id',
        'type_id',
        'name',
        'description',
        'retail_price',
        'sale_price',
        'likes',
        'competitor_flag',
        'recomended',
        'curated',
        'author'
    ];

    public function catalog() {
        return $this->belongsTo( 'App\Catalog', 'catalog_id', 'id' );
    }
}
