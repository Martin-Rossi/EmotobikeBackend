<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogsTable extends Migration {

    public function up() {
        Schema::create( 'catalogs', function( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'collection_id' )->references( 'id' )->on( 'collections' )->default( 0 );
            $table->string( 'name', 255 );
            $table->string( 'title', 255 );
            $table->integer( 'likes' )->default( 0 );
            $table->integer( 'comments' )->default( 0 );
            $table->integer( 'follows' )->default( 0 );
            $table->integer( 'author' )->references( 'id' )->on( 'users' );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'catalogs' );
    }
}
