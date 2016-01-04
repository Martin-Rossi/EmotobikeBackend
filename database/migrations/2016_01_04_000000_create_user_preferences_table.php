<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPreferencesTable extends Migration {

    public function up() {
        Schema::create( 'user_preferences', function( Blueprint $table ) {
            $table->bigIncrements( 'id' );
            $table->integer( 'user_id' )->references( 'id' )->on( 'users' );
            $table->string( 'key', 55 );
            $table->enum( 'value', [0, 1] )->default( 0 );
            $table->timestamps();

            $table->unique( ['user_id', 'key'] );
        } );
    }

    public function down() {
        Schema::drop( 'user_preferences' );
    }
}
