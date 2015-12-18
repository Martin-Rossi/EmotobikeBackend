<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration {

    public function up() {
        Schema::create( 'activities', function( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'catalog_id' )->references( 'id' )->on( 'catalogs' );
            $table->integer( 'type_id' )->references( 'id' )->on( 'types' );
            $table->string( 'name', 255 );
            $table->text( 'description' )->nullable()->default( null );
            $table->integer( 'link_to' )->default( 0 );
            $table->integer( 'link_from' )->default( 0 );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'activities' );
    }
}
