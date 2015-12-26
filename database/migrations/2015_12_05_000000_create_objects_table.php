<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectsTable extends Migration {

    public function up() {
        Schema::create( 'objects', function( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'catalog_id' )->references( 'id' )->on( 'catalogs' )->default( 0 );
            $table->integer( 'category_id' )->references( 'id' )->on( 'categories' )->default( 0 );
            $table->integer( 'type_id' )->references( 'id' )->on( 'types' )->default( 0 );
            $table->string( 'tags', 255 )->nullable()->default( null );
            $table->string( 'name', 255 );
            $table->text( 'description' )->nullable()->default( null );
            $table->string( 'url', 255 )->nullable()->default( null );
            $table->string( 'image', 255 )->nullable()->default( null );
            $table->double( 'weight', 12, 4 )->nullable()->default( null );
            $table->double( 'retail_price', 12, 2 )->nullable()->default( null );
            $table->double( 'sale_price', 12, 2 )->nullable()->default( null );
            $table->double( 'offer_value', 12, 2 )->nullable()->default( null );
            $table->string( 'offer_url', 255 )->nullable()->default( null );
            $table->text( 'offer_description' )->nullable()->default( null );
            $table->datetime( 'offer_start' )->nullable()->default( null );
            $table->datetime( 'offer_stop' )->nullable()->default( null );
            $table->string( 'prod_detail_url', 255 )->nullable()->default( null );
            $table->string( 'layout', 55 )->nullable()->default( null );
            $table->string( 'position', 55 )->nullable()->default( null );
            $table->integer( 'count_likes' )->default( 0 );
            $table->integer( 'count_comments' )->default( 0 );
            $table->integer( 'count_follows' )->default( 0 );
            $table->enum( 'competitor_flag', [0, 1] )->default( 0 );
            $table->enum( 'recomended', [0, 1] )->default( 0 );
            $table->enum( 'curated', [0, 1] )->default( 0 );
            $table->integer( 'author' )->references( 'id' )->on( 'users' );
            $table->enum( 'status', [-1, 0, 1] )->default( 1 );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'objects' );
    }
}
