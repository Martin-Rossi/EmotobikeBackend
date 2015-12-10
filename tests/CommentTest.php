<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentTest extends TestCase {

    use WithoutMiddleware;

    public function testAddComment() {
        $comment = factory( App\Comment::class, 1 )->make()->toArray();

        $user = factory( App\User::class )->create();

        $response = $this->actingAs( $user )->call( 'POST', '/comments', $comment );

        $this->seeInDatabase( 'comments', ['text' => $comment['text']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testUpdateObject() {
        $comment = factory( App\Comment::class, 1 )->create();
        $data = factory( App\Comment::class, 1 )->make()->toArray();

        $user = factory( App\User::class )->create();

        $comment->author = $user->id;
        $comment->save();

        $response = $this->actingAs( $user )->call( 'PUT', '/comments/' . $comment->id, $data );

        $this->seeInDatabase( 'comments', ['text' => $data['text']] )
             ->assertEquals( 200, $response->status() );
    }

}
