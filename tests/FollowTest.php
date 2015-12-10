<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FollowTest extends TestCase {

    use WithoutMiddleware;

    public function testDestroyFollow() {
        $follow = factory( App\Follow::class, 1 )->create();
        $user = factory( App\User::class )->create();

        $follow->author = $user->id;
        $follow->save();

        $response = $this->actingAs( $user )->call( 'DELETE', '/follows/' . $follow->id );

        $this->see( 'Follow deleted successfully' )
             ->assertEquals( 200, $response->status() );
    }

}
