<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectsTable extends Migration {

    public function up() {
        Schema::create( 'objects', function( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'catalog_id' )->references( 'id' )->on( 'catalogs' )->default( 0 );
            $table->integer( 'category_id' )->references( 'id' )->on( 'categories' )->default( 0 );
            $table->integer( 'type_id' )->references( 'id' )->on( 'types' );
            $table->string( 'name', 255 );
            $table->text( 'description' )->nullable()->default( null );
            $table->double( 'retail_price', 12, 2 )->nullable()->default( null );
            $table->double( 'sale_price', 12, 2 )->nullable()->default( null );
            $table->integer( 'likes' )->default( 0 );
            $table->integer( 'comments' )->default( 0 );
            $table->integer( 'follows' )->default( 0 );
            $table->enum( 'competitor_flag', [0, 1] )->default( 0 );
            $table->enum( 'recomended', [0, 1] )->default( 0 );
            $table->enum( 'curated', [0, 1] )->default( 0 );
            $table->integer( 'author' )->references( 'id' )->on( 'users' );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'objects' );
    }
}
