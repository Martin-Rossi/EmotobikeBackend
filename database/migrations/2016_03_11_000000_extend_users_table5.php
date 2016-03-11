<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendUsersTable5 extends Migration {

    public function up() {
        Schema::table( 'users', function ( $table ) {
        	$table->enum( 'redeem_state', [0, 1] )->default( 0 );
        } );
    }

}
