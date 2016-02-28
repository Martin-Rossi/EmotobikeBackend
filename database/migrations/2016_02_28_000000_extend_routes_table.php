<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendRoutesTable extends Migration {

    public function up() {
        Schema::table( 'routes', function ( $table ) {
        	$table->integer( 'catalog_id' )->default( 0 );
        } );
    }

}
