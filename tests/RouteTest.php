<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RouteTest extends TestCase {

    use WithoutMiddleware;

    public function testIndexRoutes() {
        $route = factory( App\Route::class, 1 )->create();

        $user = \App\User::find( $route->author );

        $this->actingAs( $user )->visit( '/routes' )
             ->see( $route->name );
    }

    public function testShowCategory() {
        $route = factory( App\Route::class, 1 )->create();

        $user = \App\User::find( $route->author );

        $this->actingAs( $user )->visit( '/routes/' . $route->id )
             ->see( $route->name );
    }

    public function testAddRoute() {
        $route = factory( App\Route::class, 1 )->make()->toArray();

        $user = factory( App\User::class )->create();

        $response = $this->actingAs( $user )->call( 'POST', '/routes', $route );

        $this->seeInDatabase( 'routes', ['name' => $route['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testUpdateRoute() {
        $route = factory( App\Route::class, 1 )->create();
        $data = factory( App\Route::class, 1 )->make()->toArray();

        $user = \App\User::find( $route->author );

        $response = $this->actingAs( $user )->call( 'PUT', '/routes/' . $route->id, $data );

        $this->seeInDatabase( 'routes', ['name' => $data['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testDeleteRoute() {
        $route = factory( App\Route::class, 1 )->create();

        $user = \App\User::find( $route->author );

        $response = $this->actingAs( $user )->call( 'DELETE', '/routes/' . $route->id );

        $this->seeInDatabase( 'routes', ['id' => $route->id, 'status' => -1] )
             ->assertEquals( 200, $response->status() );
    }

    public function testIndexDeletedRoutes() {
        $route = factory( App\Route::class, 1 )->create();

        $route->status = -1;
        $route->save();

        $user = \App\User::find( $route->author );

        $this->actingAs( $user )->visit( '/deleted/routes' )
             ->seeJson( ['type' => 'result'] );
    }

    public function testIndexRouteObjects() {
        $route = factory( App\Route::class, 1 )->create();
        
        $user = \App\User::find( $route->author );
        $object = \App\Object::find( $route->object_ids );

        $this->actingAs( $user )->visit( '/routes/' . $route->id . '/objects' )
             ->see( $object->name );
    }

}
