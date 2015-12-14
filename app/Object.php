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
        'layout',
        'position',
        'competitor_flag',
        'recomended',
        'curated',
        'author'
    ];

    public function catalog() {
        return $this->belongsTo( 'App\Catalog', 'catalog_id', 'id' );
    }

    public function type() {
        return $this->belongsTo( 'App\Type', 'type_id', 'id' );
    }

    public function comments() {
        $comments = \App\Comment::where( 'foreign_id', '=', $this->id )
                                ->where( 'foreign_type', '=', 'object' )
                                ->with( 'author' )
                                ->get();

        return $comments;
    }

    public function likes() {
        $likes = \App\Like::where( 'foreign_id', '=', $this->id )
                         ->where( 'foreign_type', '=', 'object' )
                         ->with( 'author' )
                         ->get();

        return $likes;
    }

    public function follows() {
        $follows = \App\Follow::where( 'foreign_id', '=', $this->id )
                              ->where( 'foreign_type', '=', 'object' )
                              ->with( 'author' )
                              ->get();

        return $follows;
    }

}
