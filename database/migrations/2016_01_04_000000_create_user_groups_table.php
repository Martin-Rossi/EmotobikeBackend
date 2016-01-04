<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGroupsTable extends Migration {

    public function up() {
        Schema::create( 'user_groups', function( Blueprint $table ) {
            $table->increments( 'id' );
            $table->string( 'name', 55 );
            $table->text( 'caps' )->nullable()->default( null );
            $table->timestamps();
        } );

        DB::table( 'user_groups' )->insert(
            [
                'id'            => 1,
                'name'          => 'root'
            ]
        );

        DB::table( 'user_groups' )->insert(
            [
                'id'            => 2,
                'name'          => 'admin'
            ]
        );

        DB::table( 'user_groups' )->insert(
            [
                'id'            => 100,
                'name'          => 'generic'
            ]
        );
    }

    public function down() {
        Schema::drop( 'user_groups' );
    }
}
