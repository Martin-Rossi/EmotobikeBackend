<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FollowTest extends TestCase {

    use WithoutMiddleware;

    public function testDestroyFollow() {
        $follow = factory( App\Follow::class, 1 )->create();
        
        $user = \App\User::find( $follow->author );

        $response = $this->actingAs( $user )->call( 'DELETE', '/follows/' . $follow->id );

        $this->see( 'Follow deleted successfully' )
             ->assertEquals( 200, $response->status() );
    }

    public function testDestroyFollowByAdmin() {
        $follow = factory( App\Follow::class, 1 )->create();
        
        $admin = \App\User::find( 1 );

        $response = $this->actingAs( $admin )->call( 'DELETE', '/follows/' . $follow->id );

        $this->see( 'Follow deleted successfully' )
             ->assertEquals( 200, $response->status() );
    }

}
