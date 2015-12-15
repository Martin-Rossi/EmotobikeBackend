<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionsTable extends Migration {

    public function up() {
        Schema::create( 'collections', function( Blueprint $table ) {
            $table->bigIncrements( 'id' );
            $table->integer( 'collection_id' );
            $table->integer( 'catalog_id' )->references( 'id' )->on( 'catalogs' );
            $table->integer( 'author' )->references( 'id' )->on( 'users' );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'collections' );
    }
}
