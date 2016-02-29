<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendUsersTable4 extends Migration {

    public function up() {
        Schema::table( 'users', function ( $table ) {
        	$table->integer( 'proj_earning_to_date' )->default( 0 );
        	$table->integer( 'proj_earning_overall' )->default( 0 );
        	$table->integer( 'proj_place_to_date' )->default( 0 );
        	$table->integer( 'proj_place_overall' )->default( 0 );
        } );
    }

}
