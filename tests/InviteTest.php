<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InviteTest extends TestCase {

    use WithoutMiddleware;

    public function testInvite() {
        $user = factory( App\User::class, 1 )->create();

        $data = [
            'email' => 'someone@testings.com'
        ];

        $response = $this->actingAs( $user )->call( 'POST', '/invites', $data );

        $this->seeInDatabase( 'invites', ['email' => $data['email'], 'author' => $user->id] )
             ->assertEquals( 200, $response->status() );
    }
}
