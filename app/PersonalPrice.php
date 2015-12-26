<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalPrice extends Model {

    protected $table = 'personal_prices';

    protected $fillable = [
        'user_id',
        'object_id',
        'personal_price'
    ];

}
