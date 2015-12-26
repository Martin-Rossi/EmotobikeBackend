<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalPricesTable extends Migration {

    public function up() {
        Schema::create( 'personal_prices', function( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'user_id' )->references( 'id' )->on( 'users' );
            $table->integer( 'object_id' )->references( 'id' )->on( 'objects' );
            $table->double( 'personal_price', 12, 2 );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'personal_prices' );
    }

}
