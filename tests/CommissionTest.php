<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommissionTest extends TestCase {

    use WithoutMiddleware;

    public function testIndexCommission() {
        $commission = factory( App\Commission::class, 1 )->create();

        $user = \App\User::find( 1 );

        $this->actingAs( $user )->visit( '/commissions' )
             ->seeJson( ['type' => 'result'] );
    }

    public function testShowCommission() {
        $commission = factory( App\Commission::class, 1 )->create();

        $user = \App\User::find( 1 );

        $this->actingAs( $user )->visit( '/commissions/' . $commission->id )
             ->see( $commission->product_sales );
    }

    public function testAddCommission() {
        $commission = factory( App\Commission::class, 1 )->make()->toArray();

        $user = \App\User::find( 1 );

        $response = $this->actingAs( $user )->call( 'POST', '/commissions', $commission );

        $this->seeInDatabase( 'commissions', ['user_id' => $commission['user_id'], 'product_sales' => $commission['product_sales']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testFilterObjects() {
        $commission = factory( App\Commission::class, 1 )->create();

        $catalog = \App\Catalog::find( $commission->catalog_id );

        $data = [
            'filter'   => 'catalog_id',
            'operator' => '=',
            'value'    => $catalog->id
        ];

        $user = \App\User::find( 1 );

        $response = $this->actingAs( $user )->call( 'POST', '/filter/commissions', $data );
            
        $this->seeJson( ['type' => 'result'] );
    }

}
