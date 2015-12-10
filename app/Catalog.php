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

}
