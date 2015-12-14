<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model {

    protected $table = 'catalogs';

    protected $fillable = [
        'collection_id',
        'catalog_id',
        'type_id',
        'name',
        'title',
        'author'
    ];

    public function category() {
        return $this->belongsTo( 'App\Category', 'category_id', 'id' );
    }

    public function type() {
        return $this->belongsTo( 'App\Type', 'type_id', 'id' );
    }

    public function author() {
        return $this->belongsTo( 'App\User', 'author', 'id' );
    }

    public function objects() {
        return $this->hasMany( 'App\Object', 'catalog_id', 'id' )->with( 'category', 'type', 'author' );
    }

    public function comments() {
        $comments = \App\Comment::where( 'foreign_id', '=', $this->id )
                                ->where( 'foreign_type', '=', 'catalog' )
                                ->with( 'author' )
                                ->get();

        return $comments;
    }

    public function likes() {
        $likes = \App\Like::where( 'foreign_id', '=', $this->id )
                         ->where( 'foreign_type', '=', 'catalog' )
                         ->with( 'author' )
                         ->get();

        return $likes;
    }

    public function follows() {
        $follows = \App\Follow::where( 'foreign_id', '=', $this->id )
                              ->where( 'foreign_type', '=', 'catalog' )
                              ->with( 'author' )
                              ->get();

        return $follows;
    }

}
