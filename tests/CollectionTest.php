<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CollectionTest extends TestCase {

    use WithoutMiddleware;

    public function testIndexCollections() {
        $collection = factory( App\Collection::class, 1 )->create();
        $user = factory( App\User::class, 1 )->create();

        $collection->author = $user->id;
        $collection->save();

        $this->actingAs( $user )->visit( '/collections' )
             ->see( $collection->collection_id )
             ->see( $collection->catalog_id )
             ->see( $collection->author );
    }

    public function testShowCollection() {
        $collection = factory( App\Collection::class, 1 )->create();
        $user = factory( App\User::class, 1 )->create();

        $collection->author = $user->id;
        $collection->save();

        $this->actingAs( $user )->visit( '/collections/' . $collection->collection_id )
             ->see( $collection->collection_id )
             ->see( $collection->catalog_id )
             ->see( $collection->author );
    }

    public function testAddCollection() {
        $collection = factory( App\Collection::class, 1 )->make()->toArray();
        $user = factory( App\User::class )->create();

        $response = $this->actingAs( $user )->call( 'POST', '/collections', $collection );

        $this->seeInDatabase( 'collections', ['catalog_id' => $collection['catalog_id'], 'author' => $user->id] )
             ->assertEquals( 200, $response->status() );
    }

    public function testIndexCollectionCatalogs() {
        $collection = factory( App\Collection::class, 1 )->create();
        $catalog = factory( App\Catalog::class, 1 )->create();
        $user = factory( App\User::class, 1 )->create();

        $collection->catalog_id = $catalog->id;
        $collection->author = $user->id;
        $collection->save();

        $this->actingAs( $user )->visit( '/collections/' . $collection->collection_id . '/catalogs' )
             ->see( $catalog->name );
    }

    public function testAddCatalogToCollection() {
        $collection = factory( App\Collection::class, 1 )->create();
        $catalog = factory( App\Catalog::class, 1 )->create();
        $user = factory( App\User::class )->create();

        $data['catalog_id'] = $catalog->id;

        $response = $this->actingAs( $user )->call( 'POST', '/collections/' . $collection->collection_id . '/add', $data );

        $this->seeInDatabase( 'collections', ['collection_id' => $collection->collection_id, 'catalog_id' => $catalog->id, 'author' => $user->id] )
             ->assertEquals( 200, $response->status() );
    }

    public function testRemoveCatalogToCollection() {
        $collection = factory( App\Collection::class, 1 )->create();
        $catalog = factory( App\Catalog::class, 1 )->create();
        $user = factory( App\User::class )->create();

        $collection->catalog_id = $catalog->id;
        $collection->author = $user->id;
        $collection->save();

        $data['catalog_id'] = $catalog->id;

        $response = $this->actingAs( $user )->call( 'POST', '/collections/' . $collection->collection_id . '/remove', $data );

        $this->notSeeInDatabase( 'collections', ['collection_id' => $collection->collection_id, 'catalog_id' => $catalog->id, 'author' => $user->id] )
             ->assertEquals( 200, $response->status() );
    }

}
