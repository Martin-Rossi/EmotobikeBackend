<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendMessagesTable extends Migration {

    public function up() {
        Schema::table( 'messages', function ( $table ) {
            $table->integer( 'about' )->default( 0 );
        } );
    }

}
