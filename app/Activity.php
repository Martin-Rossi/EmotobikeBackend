<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model {

    protected $table = 'activities';

    protected $fillable = [
        'catalog_id',
        'type_id',
        'name',
        'description',
        'link_to',
        'link_from'
    ];
    
    public function catalog() {
    	return $this->belongsTo( 'App\Catalog', 'catalog_id', 'id' );
    }

}
