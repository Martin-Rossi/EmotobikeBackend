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
        'image',
        'commissions_earned',
        'commission_rate',
        'personal_price_earned',
        'price_earner'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function objects() {
        return $this->hasMany( 'App\Object', 'author', 'id' )->with( 'catalog' );
    }

    public function catalogs() {
        return $this->hasMany( 'App\Catalog', 'author', 'id' )->with( 'objects' );
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

    public function follows() {
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

}
