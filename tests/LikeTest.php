<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LikeTest extends TestCase {

    use WithoutMiddleware;

    public function testDestroyLike() {
        $like = factory( App\Like::class, 1 )->create();
        $user = factory( App\User::class )->create();

        $like->author = $user->id;
        $like->save();

        $response = $this->actingAs( $user )->call( 'DELETE', '/likes/' . $like->id );

        $this->see( 'Like deleted successfully' )
             ->assertEquals( 200, $response->status() );
    }

}
