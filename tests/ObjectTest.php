<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ObjectTest extends TestCase {

    use WithoutMiddleware;

    public function testIndexObjects() {
        $object = factory( App\Object::class, 1 )->create();

        $this->visit( '/objects' )
             ->see( $object->name );
    }

    public function testShowObjects() {
        $object = factory( App\Object::class, 1 )->create();

        $this->visit( '/objects/' . $object->id )
             ->see( $object->name );
    }

    public function testAddObject() {
        $object = factory( App\Object::class, 1 )->make()->toArray();

        $response = $this->call( 'POST', '/objects', $object );

        $this->seeInDatabase( 'objects', ['name' => $object['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testUpdateObject() {
        $object = factory( App\Object::class, 1 )->create();
        $data = factory( App\Object::class, 1 )->make()->toArray();

        $response = $this->call( 'PUT', '/objects/' . $object->id, $data );

        $this->seeInDatabase( 'objects', ['name' => $data['name']] )
             ->assertEquals( 200, $response->status() );
    }

}
