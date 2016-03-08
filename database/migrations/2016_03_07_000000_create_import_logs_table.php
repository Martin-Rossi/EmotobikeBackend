<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportLogsTable extends Migration {

    public function up() {
        Schema::create( 'import_logs', function( Blueprint $table ) {
            $table->bigIncrements( 'id' );
            $table->string( 'log', 55 );
            $table->string( 'type', 55 );
            $table->text( 'object' );
            $table->text( 'message' );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::drop( 'import_logs' );
    }
}
