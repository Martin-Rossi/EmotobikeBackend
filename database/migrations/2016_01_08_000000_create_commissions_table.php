<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommissionsTable extends Migration {

    public function up() {
        Schema::create( 'commissions', function( Blueprint $table ) {
            $table->bigIncrements( 'id' );
            $table->integer( 'user_id' )->references( 'id' )->on( 'users' );
            $table->integer( 'catalog_id' )->references( 'id' )->on( 'catalogs' );
            $table->double( 'commission', 15, 8 );
            $table->double( 'commission_accrued', 15, 8 );
            $table->double( 'commission_rate', 15, 8 );
            $table->double( 'product_sales', 15, 8 );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'commissions' );
    }
}
