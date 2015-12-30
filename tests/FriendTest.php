<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FriendTest extends TestCase {

    use WithoutMiddleware;

    public function testFriendRequests() {
        $friendship = factory( App\Friend::class, 1 )->create();

        $friendship->from_accepted = 1;
        $friendship->to_accepted = 0;
        $friendship->save();

        $from = \App\User::find( $friendship->from_id );
        $to = \App\User::find( $friendship->to_id );

        $this->actingAs( $to )->visit( '/friends/requests' )
             ->seeJson( ['email' => $from->email] );
    }

    public function testAcceptFriendship() {
        $friendship = factory( App\Friend::class, 1 )->create();

        $friendship->from_accepted = 1;
        $friendship->to_accepted = 0;
        $friendship->save();

        $from = \App\User::find( $friendship->from_id );
        $to = \App\User::find( $friendship->to_id );

        $response = $this->actingAs( $to )->call( 'POST', '/friends/' . $friendship->id . '/accept/' );

        $this->see( 'Friendship accepted successfully' )
             ->assertEquals( 200, $response->status() );
    }

}
