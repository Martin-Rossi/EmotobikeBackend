<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CollectionTest extends TestCase {

    use WithoutMiddleware;

    public function testIndexCollections() {
        $this->visit( '/collections' )
             ->seeJson( ['type' => 'result'] );
    }

    public function testShowCollection() {
        $collection = factory( App\Collection::class, 1 )->create();
        
        $user = \App\User::find( $collection->author );

        $this->actingAs( $user )->visit( '/collections/' . $collection->collection_id )
             ->see( $collection->collection_id )
             ->see( $collection->foreign_id )
             ->see( $collection->foreign_type )
             ->see( $collection->author );
    }

    public function testAddCollection() {
        $collection = factory( App\Collection::class, 1 )->make()->toArray();
        $user = factory( App\User::class )->create();

        $response = $this->actingAs( $user )->call( 'POST', '/collections', $collection );

        $this->seeInDatabase( 'collections', ['foreign_id' => $collection['foreign_id'], 'foreign_type' => $collection['foreign_type'], 'author' => $user->id] )
             ->assertEquals( 200, $response->status() );
    }

    public function testDeleteCollection() {
        $collection = factory( App\Collection::class, 1 )->create();
        
        $user = \App\User::find( $collection->author );

        $this->actingAs( $user )->call( 'DELETE', '/collections/' . $collection->collection_id );

        $this->actingAs( $user )->visit( '/collections' )
             ->dontSeeJson( ['collection_id' => $collection->id, 'author' => $user->id] );
    }

    public function testIndexDeletedCollections() {
        $collection = factory( App\Collection::class, 1 )->create();
        
        $user = \App\User::find( $collection->author );

        $collection->status = -1;
        $collection->save();

        $this->actingAs( $user )->visit( '/deleted/collections' )
             ->see( $collection->collection_id );
    }

    public function testIndexCollectionObjects() {
        $collection = factory( App\Collection::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();
        
        $user = \App\User::find( $collection->author );

        $collection->foreign_id = $object->id;
        $collection->foreign_type = 'object';
        $collection->save();

        $this->actingAs( $user )->visit( '/collections/' . $collection->collection_id . '/objects' )
             ->see( $object->name );
    }

    public function testIndexCollectionCatalogs() {
        $collection = factory( App\Collection::class, 1 )->create();
        $catalog = factory( App\Catalog::class, 1 )->create();
        
        $user = \App\User::find( $collection->author );

        $collection->foreign_id = $catalog->id;
        $collection->foreign_type = 'catalog';
        $collection->save();

        $this->actingAs( $user )->visit( '/collections/' . $collection->collection_id . '/catalogs' )
             ->see( $catalog->name );
    }

    public function testAddObjectToCollection() {
        $collection = factory( App\Collection::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();
        $user = factory( App\User::class )->create();

        $data['object_id'] = $object->id;

        $response = $this->actingAs( $user )->call( 'POST', '/collections/' . $collection->collection_id . '/add/object', $data );

        $this->seeInDatabase( 'collections', ['collection_id' => $collection->collection_id, 'foreign_id' => $object->id, 'foreign_type' => 'object', 'author' => $user->id] )
             ->assertEquals( 200, $response->status() );
    }

    public function testAddCatalogToCollection() {
        $collection = factory( App\Collection::class, 1 )->create();
        $catalog = factory( App\Catalog::class, 1 )->create();
        $user = factory( App\User::class )->create();

        $data['catalog_id'] = $catalog->id;

        $response = $this->actingAs( $user )->call( 'POST', '/collections/' . $collection->collection_id . '/add/catalog', $data );

        $this->seeInDatabase( 'collections', ['collection_id' => $collection->collection_id, 'foreign_id' => $catalog->id, 'foreign_type' => 'catalog', 'author' => $user->id] )
             ->assertEquals( 200, $response->status() );
    }

    public function testRemoveObjectFromCollection() {
        $collection = factory( App\Collection::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();
        
        $user = \App\User::find( $collection->author );

        $collection->foreign_id = $object->id;
        $collection->foreign_type = 'object';
        $collection->save();

        $data['object_id'] = $object->id;

        $response = $this->actingAs( $user )->call( 'POST', '/collections/' . $collection->collection_id . '/remove/object', $data );

        $this->notSeeInDatabase( 'collections', ['collection_id' => $collection->collection_id, 'foreign_id' => $object->id, 'foreign_type' => 'object', 'author' => $user->id] )
             ->assertEquals( 200, $response->status() );
    }

    public function testRemoveCatalogFromCollection() {
        $collection = factory( App\Collection::class, 1 )->create();
        $catalog = factory( App\Catalog::class, 1 )->create();
        
        $user = \App\User::find( $collection->author );

        $collection->foreign_id = $catalog->id;
        $collection->foreign_type = 'catalog';
        $collection->save();

        $data['catalog_id'] = $catalog->id;

        $response = $this->actingAs( $user )->call( 'POST', '/collections/' . $collection->collection_id . '/remove/catalog', $data );

        $this->notSeeInDatabase( 'collections', ['collection_id' => $collection->collection_id, 'foreign_id' => $catalog->id, 'foreign_type' => 'catalog', 'author' => $user->id] )
             ->assertEquals( 200, $response->status() );
    }

}
