<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvitesTable extends Migration {

    public function up() {
        Schema::create( 'invites', function( Blueprint $table ) {
            $table->increments( 'id' );
            $table->string( 'email', 255 );
            $table->enum( 'accepted', [0, 1] )->default( 0 );
            $table->dateTime( 'accepted_on' )->default( '0000-00-00 00:00:00' );
            $table->integer( 'author' )->references( 'id' )->on( 'users' );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'invites' );
    }
}
