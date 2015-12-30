<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendsTable extends Migration {

    public function up() {
        Schema::create( 'friends', function( Blueprint $table ) {
            $table->bigIncrements( 'id' );
            $table->integer( 'from_id' )->references( 'id' )->on( 'users' );
            $table->enum( 'from_accepted', [0, 1] );
            $table->integer( 'to_id' )->references( 'id' )->on( 'users' );
            $table->enum( 'to_accepted', [0, 1] );
            $table->timestamps();

            $table->unique( ['from_id', 'to_id'] );
        } );
    }

    public function down() {
        Schema::drop( 'friends' );
    }
}
