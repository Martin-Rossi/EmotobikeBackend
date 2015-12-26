<?php

use App\User;
use App\Object;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PersonalPriceTest extends TestCase {

    use WithoutMiddleware;

    public function testAddPersonalPrice() {
        $pprice = factory( App\PersonalPrice::class, 1 )->make()->toArray();

        $object = Object::find( $pprice['object_id'] );
        $user = User::find( $object->author );

        $response = $this->actingAs( $user )->call( 'POST', '/pprices', $pprice );

        $this->seeInDatabase( 'personal_prices', ['user_id' => $pprice['user_id'], 'object_id' => $pprice['object_id']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testUpdatePersonalPrice() {
        $pprice = factory( App\PersonalPrice::class, 1 )->create();
        $data = factory( App\PersonalPrice::class, 1 )->make()->toArray();

        $object = Object::find( $data['object_id'] );
        $user = User::find( $object->author );

        $response = $this->actingAs( $user )->call( 'PUT', '/pprices/' . $pprice->id, $data);

        $this->seeInDatabase( 'personal_prices', ['user_id' => $data['user_id'], 'object_id' => $data['object_id']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testDeletePersonalPrice() {
        $pprice = factory( App\PersonalPrice::class, 1 )->create();

        $object = Object::find( $pprice->object_id );
        $user = User::find( $object->author );

        $response = $this->actingAs( $user )->call( 'DELETE', '/pprices/' . $pprice->id );

        $this->dontSeeInDatabase( 'personal_prices', ['user_id' => $pprice->user_id, 'object_id' => $pprice->object_id] )
             ->assertEquals( 200, $response->status() );
    }

    public function testGetObjectsPersonalPrice() {
        $pprice = factory( App\PersonalPrice::class, 1 )->create();

        $object = Object::find( $pprice->object_id );
        $user = User::find( $object->author );

        $visitor = User::find( $pprice->user_id );

        $response = $this->actingAs( $visitor )->visit( '/objects/' . $object->id )
                         ->see( $pprice['personal_price'] );
    }

}
