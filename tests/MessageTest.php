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

}