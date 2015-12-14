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
        $type = factory( App\Type::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();

        $object->type_id = $type->id;
        $object->save();

        $this->visit( '/types/' . $type->id . '/objects' )
             ->see( $object->name );
    }

    public function testIndexTypeCatalogs() {
        $type = factory( App\Type::class, 1 )->create();
        $catalog = factory( App\Catalog::class, 1 )->create();

        $catalog->type_id = $type->id;
        $catalog->save();

        $this->visit( '/types/' . $type->id . '/catalogs' )
             ->see( $catalog->name );
    }

}
