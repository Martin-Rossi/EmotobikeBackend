<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Object extends Model {

    protected $table = 'objects';

    protected $fillable = [
        'catalog_id',
        'category_id',
        'type_id',
        'tags',
        'name',
        'description',
        'url',
        'image',
        'weight',
        'sku',
        'retail_price',
        'sale_price',
        'offer_value',
        'offer_url',
        'offer_description',
        'offer_start',
        'offer_stop',
        'prod_detail_url',
        'layout',
        'position',
        'competitor_flag',
        'curated',
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

    public function catalog() {
        return $this->belongsTo( 'App\Catalog', 'catalog_id', 'id' )->with( 'category', 'type', 'author','current_user_like' );
    }

    public function current_user_like(){

        return $this->hasOne('\App\Like','foreign_id', 'id')
            ->where('foreign_type', '=', 'object' )
            ->where('author', '=', auth()->user()->id );

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

    public function recommendations() {
        $recommendations = \App\Recommendation::where( 'foreign_id', '=', $this->id )
                                              ->where( 'foreign_type', '=', 'object' )
                                              ->with( 'author' )
                                              ->get();

        return $recommendations;
    }

    public function feedbacks() {
        $feedbacks = \App\Feedback::where( 'foreign_id', '=', $this->id )
                                  ->where( 'foreign_type', '=', 'object' )
                                  ->with( 'author' )
                                  ->get();

        return $feedbacks;
    }



    public function personal_price() {
        if ( ! auth()->user() )
            return null;

        return \App\PersonalPrice::where( 'object_id', '=', $this->id )
                                 ->where( 'user_id', '=', auth()->user()->id )
                                 ->first();
    }

}
