<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MessageTest extends TestCase {

    use WithoutMiddleware;

    public function testShowMessage() {
        $message = factory( App\Message::class, 1 )->create();

        $this->visit( '/messages/' . $message->id )
             ->see( $message->message );
    }

    public function testAddMessage() {
        $message = factory( App\Message::class, 1 )->make()->toArray();

        $message['type'] = 'test';

        $user = factory( App\User::class )->create();

        $response = $this->actingAs( $user )->call( 'POST', '/messages', $message );

        $this->seeInDatabase( 'messages', ['message' => $message['message']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testReplyToAMessage() {
        $original = factory( App\Message::class, 1 )->create();
        $message = factory( App\Message::class, 1 )->make()->toArray();

        $user = \App\User::find( $original->recipient );

        $response = $this->actingAs( $user )->call( 'POST', '/messages/' . $original->id . '/reply', $message );

        $this->seeInDatabase( 'messages', ['message' => $message['message']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testDeleteMessage() {
        $message = factory( App\Message::class, 1 )->create();

        $user = \App\User::find( $message->recipient );

        $response = $this->actingAs( $user )->call( 'DELETE', '/messages/' . $message->id );

        $this->dontSeeInDatabase( 'messages', ['id' => $message->id] )
             ->assertEquals( 200, $response->status() );
    }

    public function testDeleteMessageByAdmin() {
        $message = factory( App\Message::class, 1 )->create();

        $admin = \App\User::find( 1 );

        $response = $this->actingAs( $admin )->call( 'DELETE', '/messages/' . $message->id );

        $this->dontSeeInDatabase( 'messages', ['id' => $message->id] )
             ->assertEquals( 200, $response->status() );
    }

    public function testIndexFromFollows() {
        $follow = factory( App\Follow::class, 1 )->create();
        $tofollow = factory( App\User::class, 1 )->create();

        $user = \App\User::find( $follow->author );

        $follow->foreign_id = $tofollow->id;
        $follow->foreign_type = 'user';
        $follow->save();

        $message = factory( App\Message::class, 1 )->create();

        $message->sender = $tofollow->id;
        $message->save();

        $this->actingAs( $user )->visit( '/messages/from/follows' )
             ->see( $message->message );
    }

    public function testGroupMessaging() {
        $message = factory( App\Message::class, 1 )->make()->toArray();

        $user = \App\User::find( 1 );

        $response = $this->actingAs( $user )->call( 'POST', '/messages/groups/100', $message );

        $this->seeInDatabase( 'messages', ['sender' => 1, 'message' => $message['message']] )
             ->assertEquals( 200, $response->status() );
    }

}
