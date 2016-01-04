<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentTest extends TestCase {

    use WithoutMiddleware;

    public function testShowComment() {
        $comment = factory( App\Comment::class, 1 )->create();

        $this->visit( '/comments/' . $comment->id )
             ->see( $comment->text );
    }

    public function testUpdateComment() {
        $comment = factory( App\Comment::class, 1 )->create();
        $data = factory( App\Comment::class, 1 )->make()->toArray();

        $user = \App\User::find( $comment->author );

        $response = $this->actingAs( $user )->call( 'PUT', '/comments/' . $comment->id, $data );

        $this->seeInDatabase( 'comments', ['text' => $data['text']] )
             ->assertEquals( 200, $response->status() );
    }

}
