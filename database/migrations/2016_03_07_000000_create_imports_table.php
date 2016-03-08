<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportsTable extends Migration {

    public function up() {
        Schema::create( 'imports', function( Blueprint $table ) {
            $table->bigIncrements( 'id' );
            $table->integer( 'foreign_id' );
            $table->string( 'foreign_type', 55 );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'imports' );
    }
}
