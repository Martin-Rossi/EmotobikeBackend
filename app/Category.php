<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    protected $table = 'categories';

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
