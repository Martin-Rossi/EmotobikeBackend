<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypesTable extends Migration {

    public function up() {
        Schema::create( 'types', function( Blueprint $table ) {
            $table->increments( 'id' );
            $table->string( 'name', 55 );
            $table->timestamps();
        } );

        DB::table( 'types' )->insert( ['id' => 1, 'name' => 'content'] );
        DB::table( 'types' )->insert( ['id' => 2, 'name' => 'product'] );
        DB::table( 'types' )->insert( ['id' => 3, 'name' => 'offer'] );
        DB::table( 'types' )->insert( ['id' => 4, 'name' => 'blog'] );
        DB::table( 'types' )->insert( ['id' => 5, 'name' => 'video'] );
        DB::table( 'types' )->insert( ['id' => 6, 'name' => 'image'] );
    }

    public function down() {
        Schema::drop( 'types' );
    }
}
