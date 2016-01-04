<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActivityTest extends TestCase {

    use WithoutMiddleware;

    public function testShowActivity() {
        $activity = factory( App\Activity::class, 1 )->create();

        $this->visit( '/activities/' . $activity->id )
             ->see( $activity->name );
    }

    public function testAddActivity() {
        $activity = factory( App\Activity::class, 1 )->make()->toArray();

        $response = $this->call( 'POST', '/activities', $activity );

        $this->seeInDatabase( 'activities', ['name' => $activity['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testUpdateActivity() {
        $activity = factory( App\Activity::class, 1 )->create();
        $data = factory( App\Activity::class, 1 )->make()->toArray();

        $response = $this->call( 'PUT', '/activities/' . $activity->id, $data );

        $this->seeInDatabase( 'activities', ['name' => $data['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testSearchActivities() {
        $activity = factory( App\Activity::class, 1 )->create();

        $data = [
            'term' => substr( $activity->name, 0, 4 )
        ];

        $response = $this->call( 'POST', '/search/activities', $data );
            
        $this->see( $activity->name );
    }

    public function testFilterActivities() {
        $activity = factory( App\Activity::class, 1 )->create();

        $data = [
            'filter'   => 'type_id',
            'operator' => '=',
            'value'    => $activity->type_id
        ];

        $response = $this->call( 'POST', '/filter/activities', $data );
            
        $this->see( $activity->name );
    }

}
