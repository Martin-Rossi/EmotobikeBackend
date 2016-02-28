<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract {
    
    use Authenticatable, Authorizable, CanResetPassword;

    protected $table = 'users';

    protected $fillable = [
        'tags',
        'name',
        'email',
        'password',
        'image',
        'profile_name',
        'api_paypal',
        'api_loyalty',
        'api_gift',
        'commission_rate_flag',
        'profile_description',
        'personal_price_earned',
        'price_earner',
        'chat',
        'noteworthy',
        'number_transaction',
        'trend',
        'total_earned',
        'place',
        'potential_place',
        'potential_earning',
        'total_commission',
        'catalog_contribution',
        'content_contribution',
        'total_purchase'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function group() {
        return $this->belongsTo( 'App\UserGroup', 'group_id' );
    }

    public function objects() {
        return $this->hasMany( 'App\Object', 'author', 'id' )->with( 'catalog' )->with( 'author' );
    }

    public function catalogs() {
        return $this->hasMany( 'App\Catalog', 'author', 'id' )->with( 'objects' )->with( 'author' );
    }

    public function collections() {
        return $this->hasMany( 'App\Collection', 'author', 'id' );
    }

    public function comments() {
        return $this->hasMany( 'App\Comment', 'author', 'id' );
    }

    public function likes() {
        return $this->hasMany( 'App\Like', 'author', 'id' );
    }

    public function following() {
        return $this->hasMany( 'App\Follow', 'author', 'id' );
    }

    public function feedbacks() {
        return $this->hasMany( 'App\Feedback', 'author', 'id' );
    }

    public function inbox() {
        return $this->hasMany( 'App\Message', 'recipient', 'id' );
    }

    public function outbox() {
        return $this->hasMany( 'App\Message', 'sender', 'id' );
    }

    public function invites() {
        return $this->hasMany( 'App\Invite', 'author', 'id' );
    }

    public function follows() {
        $follows = \App\Follow::where( 'foreign_id', '=', $this->id )
                              ->where( 'foreign_type', '=', 'user' )
                              ->with( 'author' )
                              ->get();

        return $follows;
    }

    public function parent() {
        return $this->where( 'id', '=', $this->parent_id )->first();
    }

    public function children() {
        return $this->where( 'parent_id', '=', $this->id )->get();
    }

}
