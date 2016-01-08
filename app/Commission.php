<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model {

    protected $table = 'commissions';

    protected $fillable = [
        'user_id',
        'catalog_id',
        'commission',
        'commission_accrued',
        'commission_rate',
        'product_sales'
    ];

    public function user() {
        return $this->belongsTo( 'App\User', 'user_id', 'id' );
    }

    public function catalog() {
        return $this->belongsTo( 'App\Catalog', 'catalog_id', 'id' );
    }

}
