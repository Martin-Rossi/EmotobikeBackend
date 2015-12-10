<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {

    public function up() {
        Schema::create( 'comments', function( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'foreign_id' );
            $table->enum( 'foreign_type', ['object', 'catalog'] );
            $table->text( 'text' );
            $table->integer( 'author' )->references( 'id' )->on( 'users' );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'comments' );
    }
}
