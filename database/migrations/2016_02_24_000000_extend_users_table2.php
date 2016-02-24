<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendUsersTable2 extends Migration {

    public function up() {
        Schema::table( 'users', function ( $table ) {
        	$table->integer( 'number_transaction' )->default( 0 );
        	$table->enum( 'trend', [0, 1] )->default( 0 );
			$table->integer( 'total_earned' )->default( 0 );
			$table->integer( 'place' )->default( 0 );
			$table->integer( 'potential_place' )->default( 0 );
			$table->integer( 'potential_earning' )->default( 0 );
			$table->integer( 'total_commission' )->default( 0 );
			$table->integer( 'catalog_contribution' )->default( 0 );
			$table->integer( 'content_contribution' )->default( 0 );
        } );
    }

}
