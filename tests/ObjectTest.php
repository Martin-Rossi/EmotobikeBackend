<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ObjectTest extends TestCase {

    use WithoutMiddleware;

    public function testIndexObjects() {
        $user = \App\User::find( 1 );

        $this->actingAs( $user )->visit( '/objects' )
             ->seeJson( ['type' => 'result'] );
    }

    public function testShowObject() {
        $user = \App\User::find( 1 );

        $object = factory( App\Object::class, 1 )->create();

        $this->actingAs( $user )->visit( '/objects/' . $object->id )
             ->see( $object->name );
    }

    public function testAddObject() {
        $object = factory( App\Object::class, 1 )->make()->toArray();

        $object['category'] = 'test';
        $object['type'] = 'test';

        $user = factory( App\User::class )->create();

        $response = $this->actingAs( $user )->call( 'POST', '/objects', $object );

        $this->seeInDatabase( 'objects', ['name' => $object['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testUpdateObject() {
        $object = factory( App\Object::class, 1 )->create();
        $data = factory( App\Object::class, 1 )->make()->toArray();

        $data['category'] = 'test';
        $data['type'] = 'test';

        $user = \App\User::find( $object->author );

        $response = $this->actingAs( $user )->call( 'PUT', '/objects/' . $object->id, $data );

        $this->seeInDatabase( 'objects', ['name' => $data['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testUpdateObjectByCurator() {
        $object = factory( App\Object::class, 1 )->create();
        $data = factory( App\Object::class, 1 )->make()->toArray();

        $data['category'] = 'test';
        $data['type'] = 'test';

        $user = \App\User::find( $object->author );

        $curator = factory( App\User::class, 1 )->create();

        $curator->parent_id= $user->id;
        $curator->save();

        $response = $this->actingAs( $curator )->call( 'PUT', '/objects/' . $object->id, $data );

        $this->seeInDatabase( 'objects', ['name' => $data['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testUpdateObjectByParent() {
        $object = factory( App\Object::class, 1 )->create();
        $data = factory( App\Object::class, 1 )->make()->toArray();

        $data['category'] = 'test';
        $data['type'] = 'test';

        $curator = \App\User::find( $object->author );

        $parent = factory( App\User::class, 1 )->create();

        $curator->parent_id = $parent->id;
        $curator->save();

        $response = $this->actingAs( $parent )->call( 'PUT', '/objects/' . $object->id, $data );

        $this->seeInDatabase( 'objects', ['name' => $data['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testUpdateObjectByAdmin() {
        $object = factory( App\Object::class, 1 )->create();
        $data = factory( App\Object::class, 1 )->make()->toArray();

        $data['category'] = 'test';
        $data['type'] = 'test';

        $admin = \App\User::find( 1 );

        $response = $this->actingAs( $admin )->call( 'PUT', '/objects/' . $object->id, $data );

        $this->seeInDatabase( 'objects', ['name' => $data['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testDeleteObject() {
        $object = factory( App\Object::class, 1 )->create();
        
        $user = \App\User::find( $object->author );

        $this->actingAs( $user )->call( 'DELETE', '/objects/' . $object->id );

        $this->actingAs( $user )->visit( '/objects' )
             ->dontSeeJson( ['id' => $object->id] );
    }

    public function testDeleteObjectByCurator() {
        $object = factory( App\Object::class, 1 )->create();
        
        $user = \App\User::find( $object->author );

        $curator = factory( App\User::class, 1 )->create();

        $curator->parent_id = $user->id;
        $curator->save(); 

        $response = $this->actingAs( $curator )->call( 'DELETE', '/objects/' . $object->id );

        $this->seeInDatabase( 'objects', ['id' => $object->id, 'status' => -1] )
             ->assertEquals( 200, $response->status() );
    }

    public function testDeleteObjectByParent() {
        $object = factory( App\Object::class, 1 )->create();
        
        $curator = \App\User::find( $object->author );

        $parent = factory( App\User::class, 1 )->create();

        $curator->parent_id = $parent->id;
        $curator->save(); 

        $response = $this->actingAs( $parent )->call( 'DELETE', '/objects/' . $object->id );

        $this->seeInDatabase( 'objects', ['id' => $object->id, 'status' => -1] )
             ->assertEquals( 200, $response->status() );
    }

    public function testDeleteObjectByAdmin() {
        $object = factory( App\Object::class, 1 )->create();
        
        $admin = \App\User::find( 1 );

        $response = $this->actingAs( $admin )->call( 'DELETE', '/objects/' . $object->id );

        $this->seeInDatabase( 'objects', ['id' => $object->id, 'status' => -1] )
             ->assertEquals( 200, $response->status() );
    }

    public function testIndexDeletedObjects() {
        $object = factory( App\Object::class, 1 )->create();
        
        $user = \App\User::find( $object->author );

        $object->status = -1;
        $object->save();

        $this->actingAs( $user )->visit( '/deleted/objects' )
             ->seeJson( ['type' => 'result'] );
    }

    public function testIndexDeletedObjectsByCurator() {
        $object = factory( App\Object::class, 1 )->create();
        
        $user = \App\User::find( $object->author );

        $object->status = -1;
        $object->save();

        $curator = factory( App\User::class, 1 )->create();

        $curator->parent_id = $user->id;
        $curator->save(); 

        $this->actingAs( $curator )->visit( '/deleted/objects' )
             ->seeJson( ['type' => 'result'] );
    }

    public function testIndexDeletedObjectsByParent() {
        $object = factory( App\Object::class, 1 )->create();
        
        $curator = \App\User::find( $object->author );

        $object->status = -1;
        $object->save();

        $parent = factory( App\User::class, 1 )->create();

        $curator->parent_id = $parent->id;
        $curator->save(); 

        $this->actingAs( $parent )->visit( '/deleted/objects' )
             ->seeJson( ['type' => 'result'] );
    }

    public function testSearchObjects() {
        $user = \App\User::find( 1 );

        $object = factory( App\Object::class, 1 )->create();

        $data = [
            'term' => substr( $object->name, 0, 4 )
        ];

        $response = $this->actingAs( $user )->call( 'POST', '/search/objects', $data );
            
        $this->seeJson( ['type' => 'result'] );
    }

    public function testFilterObjects() {
        $user = \App\User::find( 1 );

        $object = factory( App\Object::class, 1 )->create();

        $catalog = \App\Catalog::find( $object->catalog_id );

        $data = [
            'filter'   => 'catalog_id',
            'operator' => '=',
            'value'    => $catalog->id
        ];

        $response = $this->actingAs( $user )->call( 'POST', '/filter/objects', $data );
            
        $this->seeJson( ['type' => 'result'] );
    }

    public function testIndexObjectsCatalog() {
        $user = \App\User::find( 1 );

        $object = factory( App\Object::class, 1 )->create();

        $catalog = \App\Catalog::find( $object->catalog_id );

        $this->actingAs( $user )->visit( '/objects/' . $object->id . '/catalog' )
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

    public function testRecommendObject() {
        $object = factory( App\Object::class, 1 )->create();
        $user = factory( App\User::class )->create();

        $response = $this->actingAs( $user )->call( 'POST', '/objects/' . $object->id . '/recommend' );

        $this->seeInDatabase( 'recommendations', ['foreign_id' => $object->id, 'foreign_type' => 'object', 'author' => $user->id] )
             ->assertEquals( 200, $response->status() );
    }

    public function testIndexObjectRecommendations() {
        $recommendation = factory( App\Recommendation::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();

        $recommendation->foreign_id = $object->id;
        $recommendation->foreign_type = 'object';
        $recommendation->save();

        $user = \App\User::find( $recommendation->author );

        $this->visit( '/objects/' . $object->id . '/recommendations' )
             ->see( $user->email );
    }

    public function testFeedbackObject() {
        $object = factory( App\Object::class, 1 )->create();
        $user = factory( App\User::class )->create();

        $data = factory( App\Feedback::class )->make()->toArray();

        $response = $this->actingAs( $user )->call( 'POST', '/objects/' . $object->id . '/feedback', $data );

        $this->seeInDatabase( 'feedbacks', ['foreign_id' => $object->id, 'foreign_type' => 'object', 'author' => $user->id] )
             ->assertEquals( 200, $response->status() );
    }

    public function testIndexObjectFeedbacks() {
        $feedback = factory( App\Feedback::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();

        $feedback->foreign_id = $object->id;
        $feedback->foreign_type = 'object';
        $feedback->save();

        $user = \App\User::find( $feedback->author );

        $this->visit( '/objects/' . $object->id . '/feedbacks' )
             ->see( $user->email );
    }

}
