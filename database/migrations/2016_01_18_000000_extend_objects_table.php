<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendObjectsTable extends Migration {

    public function up() {
        Schema::table( 'objects', function ( $table ) {
            $table->string( 'sku', 55 )->nullable()->default( null );
        } );
    }
}
