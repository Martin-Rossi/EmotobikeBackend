<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration {

    public function up() {
        Schema::create( 'messages', function( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'type_id' )->references( 'id' )->on( 'types' )->default( 0 );
            $table->integer( 'message_thread' )->default( 0 );
            $table->integer( 'message_thread_id' )->default( 0 );
            $table->integer( 'sender' )->references( 'id' )->on( 'users' );
            $table->integer( 'recipient' )->references( 'id' )->on( 'users' );
            $table->text( 'message' );
            $table->string( 'image', 255 )->nullable()->default( null );
            $table->string( 'actstem', 255 )->nullable()->default( null );
            $table->integer( 'count_trendup' )->default( 0 );
            $table->integer( 'count_trenddown' )->default( 0 );
            $table->integer( 'count_replies' )->default( 0 );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'messages' );
    }
}
