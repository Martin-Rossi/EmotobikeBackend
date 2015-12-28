<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowsTable extends Migration {

    public function up() {
        Schema::create( 'follows', function( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'foreign_id' );
            $table->enum( 'foreign_type', ['object', 'catalog', 'user'] );
            $table->integer( 'author' )->references( 'id' )->on( 'users' );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'follows' );
    }
}
