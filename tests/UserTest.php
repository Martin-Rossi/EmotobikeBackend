<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase {

    use WithoutMiddleware;

    public function testShowUser() {
        $user = factory( App\User::class, 1 )->create();

        $this->visit( '/users/' . $user->id )
             ->see( $user->name );
    }

    public function testUpdateUser() {
        $user = factory( App\User::class, 1 )->create();
        $data = factory( App\User::class, 1 )->make()->toArray();

        $response = $this->actingAs( $user )->call( 'PUT', '/users/' . $user->id, $data );

        $this->seeInDatabase( 'users', ['name' => $data['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testSearchUsers() {
        $user = factory( App\User::class, 1 )->create();

        $data = [
            'term' => substr( $user->name, 0, 4 )
        ];

        $response = $this->call( 'POST', '/search/users', $data );
            
        $this->see( $user->name );
    }

    public function testFilterUsers() {
        $user = factory( App\User::class, 1 )->create();

        $data = [
            'filter'    => 'created_at',
            'operator'  => '=',
            'value'     => $user->created_at
        ];

        $response = $this->call( 'POST', '/filter/users', $data );
            
        $this->see( $user->name );
    }

    public function testIndexUserObjects() {
        $object = factory( App\Object::class, 1 )->create();
        $user = factory( App\User::class, 1 )->create();

        $object->author = $user->id;
        $object->save();

        $this->visit( '/users/' . $user->id . '/objects' )
             ->see( $object->name );
    }

    public function testIndexUserCatalogs() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        $user = factory( App\User::class, 1 )->create();

        $catalog->author = $user->id;
        $catalog->save();

        $this->visit( '/users/' . $user->id . '/catalogs' )
             ->see( $catalog->name );
    }

    public function testIndexUserCollections() {
        $collection = factory( App\Collection::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();

        $collection->foreign_id = $object->id;
        $collection->foreign_type = 'object';
        $collection->save();

        $user = \App\User::find( $collection->author );

        $this->visit( '/users/' . $user->id . '/collections' )
             ->see( $object->id );
    }

    public function testIndexUserComments() {
        $comment = factory( App\Comment::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();

        $comment->foreign_id = $object->id;
        $comment->foreign_type = 'object';
        $comment->save();

        $user = \App\User::find( $comment->author );

        $this->visit( '/users/' . $user->id . '/comments' )
             ->see( $comment->text );
    }

    public function testIndexUserLikes() {
        $like = factory( App\Like::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();

        $like->foreign_id = $object->id;
        $like->save();

        $user = \App\User::find( $like->author );

        $this->visit( '/users/' . $user->id . '/likes' )
             ->see( $like->foreign_id );
    }

    public function testIndexUserFollowing() {
        $follow = factory( App\Follow::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();

        $follow->foreign_id = $object->id;
        $follow->save();

        $user = \App\User::find( $follow->author );

        $this->visit( '/users/' . $user->id . '/following' )
             ->see( $follow->foreign_id );
    }

    public function testIndexUserFeedbacks() {
        $feedback = factory( App\Feedback::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();

        $feedback->foreign_id = $object->id;
        $feedback->foreign_type = 'object';
        $feedback->save();

        $user = \App\User::find( $feedback->author );

        $this->visit( '/users/' . $user->id . '/feedbacks' )
             ->see( $feedback->foreign_id );
    }

    public function testFollowUser() {
        $tofollow = factory( App\User::class, 1 )->create();
        $user = factory( App\User::class )->create();

        $response = $this->actingAs( $user )->call( 'POST', '/users/' . $tofollow->id . '/follow' );

        $this->seeInDatabase( 'follows', ['foreign_id' => $tofollow->id, 'foreign_type' => 'user', 'author' => $user->id] )
             ->assertEquals( 200, $response->status() );
    }

    public function testIndexUserFollows() {
        $follow = factory( App\Follow::class, 1 )->create();
        $user = factory( App\User::class, 1 )->create();

        $follow->foreign_id = $user->id;
        $follow->foreign_type = 'user';
        $follow->save();

        $followed = \App\User::find( $follow->author );

        $this->visit( '/users/' . $user->id . '/follows' )
             ->see( $followed->email );
    }

    public function testIndexUserSentMessages() {
        $message = factory( App\Message::class, 1 )->create();

        $sender = \App\User::find( $message->sender );

        $this->visit( '/users/' . $sender->id . '/messages/sent' )
             ->see( $message->message );
    }

    public function testIndexUserReceivedMessages() {
        $message = factory( App\Message::class, 1 )->create();
        
        $recipient = \App\User::find( $message->recipient );

        $this->visit( '/users/' . $recipient->id . '/messages/received' )
             ->see( $message->message );
    }

}
