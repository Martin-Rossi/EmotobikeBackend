<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CatalogTest extends TestCase {

    use WithoutMiddleware;

    public function testIndexCatalogs() {
        $catalog = factory( App\Catalog::class, 1 )->create();

        $this->visit( '/catalogs' )
             ->see( $catalog->name );
    }

    public function testShowCatalog() {
        $catalog = factory( App\Catalog::class, 1 )->create();

        $this->visit( '/catalogs/' . $catalog->id )
             ->see( $catalog->name );
    }

    public function testAddCatalog() {
        $catalog = factory( App\Catalog::class, 1 )->make()->toArray();

        $user = factory( App\User::class )->create();

        $response = $this->actingAs( $user )->call( 'POST', '/catalogs', $catalog );

        $this->seeInDatabase( 'catalogs', ['name' => $catalog['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testUpdateCatalog() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        $data = factory( App\Catalog::class, 1 )->make()->toArray();

        $user = factory( App\User::class )->create();

        $catalog->author = $user->id;
        $catalog->save();

        $response = $this->actingAs( $user )->call( 'PUT', '/catalogs/' . $catalog->id, $data );

        $this->seeInDatabase( 'catalogs', ['name' => $data['name']] )
             ->assertEquals( 200, $response->status() );
    }

}
