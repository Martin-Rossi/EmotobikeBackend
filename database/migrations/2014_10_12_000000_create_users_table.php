<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

    public function up() {
        Schema::create( 'users', function( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'parent_id' )->default( 0 );
            $table->integer( 'group_id' )->references( 'id' )->on( 'user_groups' )->default( 200 );
            $table->string( 'tags', 255 )->nullable()->default( null );
            $table->string( 'name' );
            $table->string( 'email')->unique();
            $table->string( 'password', 60 );
            $table->string( 'image', 255 )->nullable()->default( null );
            $table->string( 'profile_name' )->nullable()->default( null );
            $table->text( 'profile_description' )->nullable()->default( null );
            $table->string( 'api_paypal', 255 )->nullable()->default( null );
            $table->string( 'api_loyalty', 255 )->nullable()->default( null );
            $table->string( 'api_gift', 255 )->nullable()->default( null );
            $table->double( 'commissions', 15, 8 )->default( 0 );
            $table->double( 'commission_rate', 12, 2 )->default( 0 );
            $table->enum( 'commission_rate_flag', [0, 1] )->default( 0 );
            $table->double( 'commission_exchange', 12, 2 )->default( 1 );
            $table->integer( 'personal_price_earned' )->default( 0 );
            $table->integer( 'price_earner' )->default( 0 );
            $table->integer( 'count_likes' )->default( 0 );
            $table->integer( 'count_following' )->default( 0 );
            $table->integer( 'count_authored' )->default( 0 );
            $table->integer( 'count_drafts' )->default( 0 );
            $table->integer( 'count_follows' )->default( 0 );
            $table->enum( 'chat', [0, 1] )->default( 0 );
            $table->rememberToken();
            $table->timestamps();
        } );

        DB::table( 'users' )->insert(
            [
                'group_id'      => 1,
                'name'          => 'root',
                'email'         => 'root@localhost',
                'password'      => bcrypt( 'test' )
            ]
        );

        DB::table( 'users' )->insert(
            [
                'group_id'      => 2,
                'name'          => 'admin',
                'email'         => 'admin@localhost',
                'password'      => bcrypt( 'test' )
            ]
        );

        DB::table( 'users' )->insert(
            [
                'group_id'      => 100,
                'name'          => 'generic',
                'email'         => 'generic@localhost',
                'password'      => bcrypt( 'test' )
            ]
        );
    }

    public function down() {
        Schema::drop( 'users' );
    }

}
