<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase {

    use WithoutMiddleware;

    public function testLogin() {
        $user = factory( App\User::class, 1 )->create();

        $data = [
            'email'     => $user->email,
            'password'  => 'test'
        ];

        $response = $this->call( 'POST', '/auth/login', $data );

        $this->seeJson( ['type' => 'success'] );
    }

    public function testFailedLogin() {
        $data = [
            'email'     => 'some@dummy-email.com',
            'password'  => 'dummy-password'
        ];

        $response = $this->call( 'POST', '/auth/login', $data );

        $this->seeJson( ['type' => 'error'] );
    }

}
