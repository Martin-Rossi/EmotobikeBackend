<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendUsersTable3 extends Migration {

    public function up() {
        Schema::table( 'users', function ( $table ) {
        	$table->double( 'total_purchase', 15, 8 )->default( 0 );
        } );
    }

}
