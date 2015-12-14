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

        $object['type'] = 'test';

        $user = factory( App\User::class )->create();

        $response = $this->actingAs( $user )->call( 'POST', '/objects', $object );

        $this->seeInDatabase( 'objects', ['name' => $object['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testUpdateObject() {
        $object = factory( App\Object::class, 1 )->create();
        $data = factory( App\Object::class, 1 )->make()->toArray();

        $data['type'] = 'test';

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

    public function testCommentObject() {
        $object = factory( App\Object::class, 1 )->create();
        $user = factory( App\User::class )->create();

        $data = factory( App\Comment::class )->make()->toArray();

        $response = $this->actingAs( $user )->call( 'POST', '/objects/' . $object->id . '/comment', $data );

        $this->seeInDatabase( 'comments', ['foreign_id' => $object->id, 'foreign_type' => 'object', 'author' => $user->id] )
             ->assertEquals( 200, $response->status() );
    }

    public function testIndexObjectComments() {
        $comment = factory( App\Comment::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();

        $comment->foreign_id = $object->id;
        $comment->foreign_type = 'object';
        $comment->save();

        $user = \App\User::find( $comment->author );

        $this->visit( '/objects/' . $object->id . '/comments' )
             ->see( $user->email );
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

    public function testFollowObject() {
        $object = factory( App\Object::class, 1 )->create();
        $user = factory( App\User::class )->create();

        $response = $this->actingAs( $user )->call( 'POST', '/objects/' . $object->id . '/follow' );

        $this->seeInDatabase( 'follows', ['foreign_id' => $object->id, 'foreign_type' => 'object', 'author' => $user->id] )
             ->assertEquals( 200, $response->status() );
    }

    public function testIndexObjectFollows() {
        $follow = factory( App\Follow::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();

        $follow->foreign_id = $object->id;
        $follow->save();

        $user = \App\User::find( $follow->author );

        $this->visit( '/objects/' . $object->id . '/follows' )
             ->see( $user->email );
    }

}
