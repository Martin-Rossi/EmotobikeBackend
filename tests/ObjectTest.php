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

    public function testShowObject() {
        $object = factory( App\Object::class, 1 )->create();

        $this->visit( '/objects/' . $object->id )
             ->see( $object->name );
    }

    public function testAddObject() {
        $object = factory( App\Object::class, 1 )->make()->toArray();

        $user = factory( App\User::class )->create();

        $response = $this->actingAs( $user )->call( 'POST', '/objects', $object );

        $this->seeInDatabase( 'objects', ['name' => $object['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testUpdateObject() {
        $object = factory( App\Object::class, 1 )->create();
        $data = factory( App\Object::class, 1 )->make()->toArray();

        $user = factory( App\User::class )->create();

        $object->author = $user->id;
        $object->save();

        $response = $this->actingAs( $user )->call( 'PUT', '/objects/' . $object->id, $data );

        $this->seeInDatabase( 'objects', ['name' => $data['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testIndexObjectsCatalog() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();

        $object->catalog_id = $catalog->id;
        $object->save();

        $this->visit( '/objects/' . $object->id . '/catalog' )
             ->see( $catalog->name );
    }

    public function testIndexObjectsComments() {
        $comment = factory( App\Comment::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();

        $comment->object_id = $object->id;
        $comment->save();

        $this->visit( '/objects/' . $object->id . '/comments' )
             ->see( $comment->text );
    }

    public function testLikeObject() {
        $object = factory( App\Object::class, 1 )->create();
        $user = factory( App\User::class )->create();

        $response = $this->actingAs( $user )->call( 'POST', '/objects/' . $object->id . '/like' );

        $this->seeInDatabase( 'likes', ['foreign_id' => $object->id, 'foreign_type' => 'object', 'author' => $user->id] )
             ->assertEquals( 200, $response->status() );
    }

    public function testIndexObjectLikes() {
        $like = factory( App\Like::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();

        $like->foreign_id = $object->id;
        $like->save();

        $user = \App\User::find( $like->author );

        $this->visit( '/objects/' . $object->id . '/likes' )
             ->see( $user->email );
    }

}
