<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TypeTest extends TestCase {

    use WithoutMiddleware;

    public function testIndexTypes() {
        $type = factory( App\Type::class, 1 )->create();

        $this->visit( '/types' )
             ->see( $type->name );
    }

    public function testShowType() {
        $type = factory( App\Type::class, 1 )->create();

        $this->visit( '/types/' . $type->id )
             ->see( $type->name );
    }

    public function testIndexTypeObjects() {
        $object = factory( App\Object::class, 1 )->create();

        $this->visit( '/types/' . $object->type_id . '/objects' )
             ->see( $object->name );
    }

    public function testIndexTypeCatalogs() {
        $catalog = factory( App\Catalog::class, 1 )->create();

        $this->visit( '/types/' . $catalog->type_id . '/catalogs' )
             ->see( $catalog->name );
    }

}
