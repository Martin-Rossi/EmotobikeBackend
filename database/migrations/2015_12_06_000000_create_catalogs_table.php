<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogsTable extends Migration {

    public function up() {
        Schema::create( 'catalogs', function( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'category_id' )->references( 'id' )->on( 'categories' )->default( 0 );
            $table->integer( 'type_id' )->references( 'id' )->on( 'types' )->default( 0 );
            $table->string( 'tags', 255 )->nullable()->default( null );
            $table->string( 'name', 255 );
            $table->string( 'title', 255 );
            $table->string( 'layout', 55 )->nullable()->default( null );
            $table->string( 'position', 55 )->nullable()->default( null );
            $table->enum( 'publish', [0, 1] )->default( 0 );
            $table->enum( 'trending', [0, 1] )->default( 0 );
            $table->enum( 'popular', [0, 1] )->default( 0 );
            $table->enum( 'recomended', [0, 1] )->default( 0 );
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
