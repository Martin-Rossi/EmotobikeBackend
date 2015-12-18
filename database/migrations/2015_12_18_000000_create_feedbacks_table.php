<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbacksTable extends Migration {

    public function up() {
        Schema::create( 'feedbacks', function( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'foreign_id' );
            $table->enum( 'foreign_type', ['object', 'catalog'] );
            $table->bigInteger( 'value' );
            $table->integer( 'author' )->references( 'id' )->on( 'users' );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'feedbacks' );
    }
}
