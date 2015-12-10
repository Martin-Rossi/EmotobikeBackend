<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Object extends Model {

    protected $table = 'objects';

    protected $fillable = [
        'catalog_id',
        'category_id',
        'type_id',
        'name',
        'description',
        'retail_price',
        'sale_price',
        'competitor_flag',
        'recomended',
        'curated',
        'author'
    ];

    public function catalog() {
        return $this->belongsTo( 'App\Catalog', 'catalog_id', 'id' );
    }

    public function comments() {
        return $this->hasMany( 'App\Comment', 'object_id', 'id' )->with( 'user' );
    }

}
