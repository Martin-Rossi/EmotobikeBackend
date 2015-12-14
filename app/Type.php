<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model {

    protected $table = 'types';

    protected $fillable = [
        'name'
    ];
    
    public function objects() {
    	return $this->hasMany( 'App\Object', 'type_id', 'id' );
    }

    public function catalogs() {
    	return $this->hasMany( 'App\Catalog', 'type_id', 'id' );
    }

}
