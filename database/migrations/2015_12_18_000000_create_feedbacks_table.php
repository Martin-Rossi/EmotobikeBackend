<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbacksTable extends Migration {

    public function up() {
        Schema::create( 'feedbacks', function( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'foreign_id' );
            $table->enum( 'foreign_type', ['object', 'catalog'] );
            $table->integer( 'product_id' )->default( 0 );
            $table->integer( 'offer_id' )->default( 0 );
            $table->integer( 'shopper_id' )->default( 0 );
            $table->integer( 'activity_id' )->default( 0 );
            $table->integer( 'interface_id' )->default( 0 );
            $table->text( 'event' )->nullable()->default( null );
            $table->text( 'channel' )->nullable()->default( null );
            $table->integer( 'channel_id' )->default( 0 );
            $table->date( 'date' )->nullable()->default( null );
            $table->time( 'time' )->nullable()->default( null );
            $table->text( 'taxonomy' )->nullable()->default( null );
            $table->text( 'behavior' )->nullable()->default( null );
            $table->double( 'behavior_frequency', 15, 8 )->default( 0 );
            $table->integer( 'artifact_id' )->default( 0 );
            $table->double( 'artifact_frequency', 15, 8 )->default( 0 );
            $table->integer( 'interaction_id' )->default( 0 );
            $table->double( 'interaction_frequency', 15, 8 )->default( 0 );
            $table->bigInteger( 'value' );
            $table->integer( 'author' )->references( 'id' )->on( 'users' );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'feedbacks' );
    }
}
