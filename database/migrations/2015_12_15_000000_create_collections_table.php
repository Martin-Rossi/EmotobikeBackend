<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionsTable extends Migration {

    public function up() {
        Schema::create( 'collections', function( Blueprint $table ) {
            $table->bigIncrements( 'id' );
            $table->integer( 'collection_id' );
            $table->integer( 'foreign_id' );
            $table->enum( 'foreign_type', ['object', 'catalog'] );
            $table->string( 'name', 255 )->default( 'untitled' );
            $table->integer( 'author' )->references( 'id' )->on( 'users' );
            $table->enum( 'status', [-1, 0, 1] )->default( 1 );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'collections' );
    }
}
