<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GenericCollectionTest extends TestCase {

    use WithoutMiddleware;

    public function testIndexGenericCollections() {
        $generic_collection = factory( App\GenericCollection::class, 1 )->create();

        $this->visit( '/generic-collections' )
             ->seeJson( ['type' => 'result'] );
    }

    public function testShowGenericCollection() {
        $generic_collection = factory( App\GenericCollection::class, 1 )->create();

        $this->visit( '/generic-collections/' . $generic_collection->collection_id )
             ->see( $generic_collection->collection_id )
             ->see( $generic_collection->foreign_id )
             ->see( $generic_collection->foreign_type );
    }

    public function testAddgenericCollection() {
        $generic_collection = factory( App\GenericCollection::class, 1 )->make()->toArray();
        
        $user = \App\User::find( 1 );

        $response = $this->actingAs( $user )->call( 'POST', '/generic-collections', $generic_collection );

        $this->seeInDatabase( 'generic_collections', ['foreign_id' => $generic_collection['foreign_id'], 'foreign_type' => $generic_collection['foreign_type']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testDeleteGenericCollection() {
        $generic_collection = factory( App\GenericCollection::class, 1 )->create();
        
        $user = \App\User::find( 1 );

        $response = $this->actingAs( $user )->call( 'DELETE', '/generic-collections/' . $generic_collection->collection_id );

        $this->seeInDatabase( 'generic_collections', ['foreign_id' => $generic_collection->foreign_id, 'foreign_type' => $generic_collection->foreign_type, 'status' => '-1'] )
             ->assertEquals( 200, $response->status() );
    }

    public function testIndexDeletedGenericCollections() {
        $generic_collection = factory( App\GenericCollection::class, 1 )->create();
        
        $user = \App\User::find( 1);

        $generic_collection->status = -1;
        $generic_collection->save();

        $this->actingAs( $user )->visit( '/deleted/generic-collections' )
             ->see( $generic_collection->collection_id );
    }

    public function testIndexGenericCollectionObjects() {
        $generic_collection = factory( App\GenericCollection::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();

        $generic_collection->foreign_id = $object->id;
        $generic_collection->foreign_type = 'object';
        $generic_collection->save();

        $this->visit( '/generic-collections/' . $generic_collection->collection_id . '/objects' )
             ->see( $object->name );
    }

    public function testIndexGenericCollectionCatalogs() {
        $generic_collection = factory( App\GenericCollection::class, 1 )->create();
        $catalog = factory( App\Catalog::class, 1 )->create();

        $generic_collection->foreign_id = $catalog->id;
        $generic_collection->foreign_type = 'catalog';
        $generic_collection->save();

        $this->visit( '/generic-collections/' . $generic_collection->collection_id . '/catalogs' )
             ->see( $catalog->name );
    }

    public function testAddObjectToGenericCollection() {
        $generic_collection = factory( App\GenericCollection::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();
        
        $user = \App\User::find( 1 );

        $data['object_id'] = $object->id;

        $response = $this->actingAs( $user )->call( 'POST', '/generic-collections/' . $generic_collection->collection_id . '/add/object', $data );

        $this->seeInDatabase( 'generic_collections', ['collection_id' => $generic_collection->collection_id, 'foreign_id' => $object->id, 'foreign_type' => 'object'] )
             ->assertEquals( 200, $response->status() );
    }

    public function testAddCatalogToGenericCollection() {
        $generic_collection = factory( App\GenericCollection::class, 1 )->create();
        $catalog = factory( App\Catalog::class, 1 )->create();
        
        $user = \App\User::find( 1 );

        $data['catalog_id'] = $catalog->id;

        $response = $this->actingAs( $user )->call( 'POST', '/generic-collections/' . $generic_collection->collection_id . '/add/catalog', $data );

        $this->seeInDatabase( 'generic_collections', ['collection_id' => $generic_collection->collection_id, 'foreign_id' => $catalog->id, 'foreign_type' => 'catalog'] )
             ->assertEquals( 200, $response->status() );
    }

    public function testRemoveObjectFromGenericCollection() {
        $generic_collection = factory( App\GenericCollection::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();
        
        $user = \App\User::find( 1 );

        $generic_collection->foreign_id = $object->id;
        $generic_collection->foreign_type = 'object';
        $generic_collection->save();

        $data['object_id'] = $object->id;

        $response = $this->actingAs( $user )->call( 'POST', '/generic-collections/' . $generic_collection->collection_id . '/remove/object', $data );

        $this->notSeeInDatabase( 'generic_collections', ['collection_id' => $generic_collection->collection_id, 'foreign_id' => $object->id, 'foreign_type' => 'object'] )
             ->assertEquals( 200, $response->status() );
    }

    public function testRemoveCatalogFromGenericCollection() {
        $generic_collection = factory( App\GenericCollection::class, 1 )->create();
        $catalog = factory( App\Catalog::class, 1 )->create();
        
        $user = \App\User::find( 1 );

        $generic_collection->foreign_id = $catalog->id;
        $generic_collection->foreign_type = 'catalog';
        $generic_collection->save();

        $data['catalog_id'] = $catalog->id;

        $response = $this->actingAs( $user )->call( 'POST', '/generic-collections/' . $generic_collection->collection_id . '/remove/catalog', $data );

        $this->notSeeInDatabase( 'generic_collections', ['collection_id' => $generic_collection->collection_id, 'foreign_id' => $catalog->id, 'foreign_type' => 'catalog'] )
             ->assertEquals( 200, $response->status() );
    }

}
