<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenericCollectionsTable extends Migration {

    public function up() {
        Schema::create( 'generic_collections', function( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'collection_id' );
            $table->integer( 'foreign_id' );
            $table->enum( 'foreign_type', ['object', 'catalog'] );
            $table->string( 'name', 255 )->default( 'untitled' );
            $table->enum( 'status', [-1, 0, 1] )->default( 1 );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'generic_collections' );
    }
}
