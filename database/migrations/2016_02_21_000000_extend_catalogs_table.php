<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendCatalogsTable extends Migration {

    public function up() {
        Schema::table( 'catalogs', function ( $table ) {
            $table->enum( 'chat', [0,1] )->nullable()->default( null );
            $table->integer( 'average_present_month' )->default( 0 );
            $table->integer( 'average_overall' )->default( 0 );
            $table->integer( 'content_score' )->default( 0 );
            $table->integer( 'sales_score' )->default( 0 );
        } );
    }
}
