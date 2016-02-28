<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model {

    protected $table = 'catalogs';

    protected $fillable = [
        'collection_id',
        'category_id',
        'type_id',
        'tags',
        'name',
        'title',
        'description',
        'image',
        'layout',
        'position',
        'publish',
        'trending',
        'popular',
        'earning_trend',
        'earning_total',
        'earning_place',
        'earning_cat_place',
        'earning_potential',
        'average_present_month',
        'average_overall',
        'content_score',
        'sales_score',
        'chat',
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
        return $this->hasMany( 'App\Object', 'catalog_id', 'id' )->with( 'category', 'type', 'author' ,'current_user_like');
    }

    public function current_user_like() {
        return $this->hasOne( '\App\Like','foreign_id', 'id' )
                    ->where( 'foreign_type', '=', 'catalog' )
                    ->where( 'author', '=', auth()->user()->id );

    }

    public function activities() {
        return $this->hasMany( 'App\Activity', 'catalog_id', 'id' );
    }

    public function products() {
        $products = \App\Object::where( 'catalog_id', '=', $this->id )
                               ->where( 'type_id', '=', 2 )
                               ->with( 'category', 'type', 'author','current_user_like' )
                               ->get();

        return $products;
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

    public function recommendations() {
        $recommendations = \App\Recommendation::where( 'foreign_id', '=', $this->id )
                                              ->where( 'foreign_type', '=', 'catalog' )
                                              ->with( 'author' )
                                              ->get();

        return $recommendations;
    }

    public function feedbacks() {
        $feedbacks = \App\Feedback::where( 'foreign_id', '=', $this->id )
                                  ->where( 'foreign_type', '=', 'catalog' )
                                  ->with( 'author' )
                                  ->get();

        return $feedbacks;
    }

    public function route() {
        return $this->belongsTo( 'App\Route', 'catalog_id', 'id' );
    }

}
