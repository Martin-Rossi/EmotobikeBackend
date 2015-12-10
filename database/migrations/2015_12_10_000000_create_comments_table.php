<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {

    public function up() {
        Schema::create( 'comments', function( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'object_id' )->references( 'id' )->on( 'objects' );
            $table->text( 'text' );
            $table->integer( 'author' )->references( 'id' )->on( 'users' );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'comments' );
    }
}
