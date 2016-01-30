<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendUsersTable extends Migration {

    public function up() {
        Schema::table( 'users', function ( $table ) {
            $table->enum( 'noteworthy', [0, 1] )->default( 0 );
        } );
    }

}
