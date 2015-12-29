<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutesTable extends Migration {

    public function up() {
        Schema::create( 'routes', function( Blueprint $table ) {
            $table->increments( 'id' );
            $table->string( 'name', 255 );
            $table->text( 'description' )->nullable()->default( null );
            $table->binary( 'data' )->nullable()->default( null );
            $table->text( 'object_ids' )->nullable()->default( null );
            $table->integer( 'author' )->references( 'id' )->on( 'users' );
            $table->enum( 'status', [-1, 0, 1] )->default( 1 );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'routes' );
    }
}
