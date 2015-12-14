<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypesTable extends Migration {

    public function up() {
        Schema::create( 'types', function( Blueprint $table ) {
            $table->increments( 'id' );
            $table->string( 'name', 55 );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'types' );
    }
}
