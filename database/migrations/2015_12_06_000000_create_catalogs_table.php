<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogsTable extends Migration {

    public function up() {
        Schema::create( 'catalogs', function( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'category_id' )->references( 'id' )->on( 'categories' )->default( 0 );
            $table->integer( 'type_id' )->references( 'id' )->on( 'types' )->default( 0 );
            $table->string( 'name', 255 );
            $table->string( 'title', 255 );
            $table->integer( 'count_likes' )->default( 0 );
            $table->integer( 'count_comments' )->default( 0 );
            $table->integer( 'count_follows' )->default( 0 );
            $table->integer( 'author' )->references( 'id' )->on( 'users' );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'catalogs' );
    }
}
