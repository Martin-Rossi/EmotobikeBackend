<?php

namespace App\Http\Controllers;

use App\GenericCollection;
use App\Object;
use App\Catalog;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class GenericCollectionController extends Controller {

    protected $pp = 10;

    public function __construct( Request $request ) {
        if ( $request->get( 'pp' ) )
            $this->pp = intval( $request->get( 'pp' ) );
    }

    public function index( ApiResponse $response ) {
        $generic_collections = GenericCollection::where( 'status', '>', 0 )
                                                ->paginate( $this->pp );

        $i = 0;
        
        foreach ( $generic_collections as $generic_collection ) {
            if ( 'object' == $generic_collection->foreign_type )
                $generic_collections[$i]->object = Object::find( $generic_collection->foreign_id )->toArray();
            else
                $generic_collections[$i]->catalog = Catalog::find( $generic_collection->foreign_id )->toArray();


            $i++;
        }

        return $response->result( $generic_collections->toArray() );
    }

    public function show( $id, ApiResponse $response ) {
        $generic_collection = GenericCollection::where( 'collection_id', '=', $id )
                                               ->get();

        return $response->result( $generic_collection->toArray() );
    }

    public function store( Request $request, ApiResponse $response ) {
        if ( auth()->user()->group_id > 2 )
            abort( 403 );

        $inputs = $request->all();

        if ( 'catalog' != $inputs['foreign_type'] && 'object' != $inputs['foreign_type'] )
            return $response->error( 'Invalid foreign type specified' );

        $last = GenericCollection::orderBy( 'collection_id', 'DESC' )
                                 ->first();

        if ( ! $last )
            $inputs['collection_id'] = 1;
        else {
            $collection_id = $last->collection_id + 1;

            $inputs['collection_id'] = $collection_id;
        }

        try {
            GenericCollection::create( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'GenericCollection created successfully' );
    }

    public function update( $id, Request $request, ApiResponse $response ) {
        $generic_collections = GenericCollection::where( 'collection_id', '=', $id )
                                                ->get();

        if ( ! sizeof( $generic_collections ) > 0 )
            abort( 404 );

        foreach ( $generic_collections as $generic_collection ) {
            $generic_collection->name = $request->get( 'name' );
            $generic_collection->save();
        }

        return $response->success( 'GenericCollection updated successfully' );
    }

    public function destroy( $id, ApiResponse $response ) {
        if ( auth()->user()->group_id > 2 )
            abort( 403 );

        $generic_collection = GenericCollection::where( 'collection_id', '=', $id )
                                               ->get();

        if ( ! sizeof( $generic_collection ) > 0 )
            abort( 404 );

        foreach ( $generic_collection as $c ) {
            $c->status = -1;
            $c->save();
        }

        return $response->success( 'GenericCollection succesfully deleted' );
    }

    public function deleted( ApiResponse $response ) {
        if ( auth()->user()->group_id > 2 )
            abort( 403 );

        $generic_collections = GenericCollection::where( 'status', '<', 0 )
                                                ->paginate( $this->pp );

        $i = 0;
        
        foreach ( $generic_collections as $generic_collection ) {
            if ( 'object' == $generic_collection->foreign_type )
                $generic_collections[$i]->object = Object::find( $generic_collection->foreign_id )->toArray();
            else
                $generic_collections[$i]->catalog = Catalog::find( $generic_collection->foreign_id )->toArray();


            $i++;
        }

        return $response->result( $generic_collections->toArray() );
    }

    public function objects( $id, ApiResponse $response ) {
        $generic_collection = GenericCollection::where( 'collection_id', '=', $id )
                                               ->where( 'foreign_type', '=', 'object' )
                                               ->get();

        $object_ids = [];

        if ( ! sizeof( $generic_collection ) > 0 )
            return $response->result( $object_ids );

        foreach ( $generic_collection as $object )
            $object_ids[] = $object->foreign_id;

        $objects = Object::whereIn( 'id', $object_ids )->with( 'catalog' )->get();

        return $response->result( $objects );
    }

    public function catalogs( $id, ApiResponse $response ) {
        $generic_collection = GenericCollection::where( 'collection_id', '=', $id )
                                               ->where( 'foreign_type', '=', 'catalog' )
                                               ->get();

        $catalog_ids = [];

        if ( ! sizeof( $generic_collection ) > 0 )
            return $response->result( $catalog_ids );

        foreach ( $generic_collection as $catalog )
            $catalog_ids[] = $catalog->foreign_id;

        $catalogs = Catalog::whereIn( 'id', $catalog_ids )->with( 'objects' )->get();

        return $response->result( $catalogs );
    }

    public function addObject( $id, Request $request, ApiResponse $response ) {
        if ( auth()->user()->group_id > 2 )
            abort( 403 );

        $inputs = $request->all();

        if ( ! isset( $inputs['object_id'] ) )
            return $response->error( 'Please specify an object id' );

        if ( ! $inputs['object_id'] )
            return $response->error( 'Please specify an object id' );

        if ( ! is_numeric( $inputs['object_id'] ) )
            return $response->error( 'Please specify an object id' );

        $exists = GenericCollection::where( 'collection_id', '=', $id )
                                   ->where( 'foreign_id', '=', $inputs['object_id'] )
                                   ->where( 'foreign_type', '=', 'object' )
                                   ->first();

        if ( $exists )
            return $response->error( 'Object already added to this generic collection' );

        $generic_collection = new GenericCollection();
        $generic_collection->collection_id = $id;
        $generic_collection->foreign_id = $inputs['object_id'];
        $generic_collection->foreign_type = 'object';

        $cn = GenericCollection::where( 'collection_id', '=', $id )
                               ->first();

        if ( $cn )
            $generic_collection->name = $cn->name;
        
        $generic_collection->save();

        return $response->success( 'Object successfully added to generic collection' );
    }

    public function addCatalog( $id, Request $request, ApiResponse $response ) {
        if ( auth()->user()->group_id > 2 )
            abort( 403 );

        $inputs = $request->all();

        if ( ! isset( $inputs['catalog_id'] ) )
            return $response->error( 'Please specify a catalog id' );

        if ( ! $inputs['catalog_id'] )
            return $response->error( 'Please specify a catalog id' );

        if ( ! is_numeric( $inputs['catalog_id'] ) )
            return $response->error( 'Please specify a catalog id' );

        $exists = GenericCollection::where( 'collection_id', '=', $id )
                                   ->where( 'foreign_id', '=', $inputs['catalog_id'] )
                                   ->where( 'foreign_type', '=', 'catalog' )
                                   ->first();

        if ( $exists )
            return $response->error( 'Catalog already added to this generic collection' );

        $generic_collection = new GenericCollection();
        $generic_collection->collection_id = $id;
        $generic_collection->foreign_id = $inputs['catalog_id'];
        $generic_collection->foreign_type = 'catalog';

        $cn = GenericCollection::where( 'collection_id', '=', $id )
                               ->first();

        if ( $cn )
            $generic_collection->name = $cn->name;
        
        $generic_collection->save();

        return $response->success( 'Catalog successfully added to generic collection' );
    }

    public function removeObject( $id, Request $request, ApiResponse $response ) {
        if ( auth()->user()->group_id > 2 )
            abort( 403 );

        $inputs = $request->all();

        if ( ! isset( $inputs['object_id'] ) )
            return $response->error( 'Please specify an object id' );

        if ( ! $inputs['object_id'] )
            return $response->error( 'Please specify an object id' );

        if ( ! is_numeric( $inputs['object_id'] ) )
            return $response->error( 'Please specify an object id' );

        $exists = GenericCollection::where( 'collection_id', '=', $id )
                                   ->where( 'foreign_id', '=', $inputs['object_id'] )
                                   ->where( 'foreign_type', '=', 'object' )
                                   ->first();

        if ( ! $exists )
            return $response->error( 'Object not in this generic collection' );

        $exists->delete();

        return $response->success( 'Object successfully removed from generic collection' );
    }

    public function removeCatalog( $id, Request $request, ApiResponse $response ) {
        if ( auth()->user()->group_id > 2 )
            abort( 403 );

        $inputs = $request->all();

        if ( ! isset( $inputs['catalog_id'] ) )
            return $response->error( 'Please specify a catalog id' );

        if ( ! $inputs['catalog_id'] )
            return $response->error( 'Please specify a catalog id' );

        if ( ! is_numeric( $inputs['catalog_id'] ) )
            return $response->error( 'Please specify a catalog id' );

        $exists = GenericCollection::where( 'collection_id', '=', $id )
                                   ->where( 'foreign_id', '=', $inputs['catalog_id'] )
                                   ->where( 'foreign_type', '=', 'catalog' )
                                   ->first();

        if ( ! $exists )
            return $response->error( 'Catalog not in this generic collection' );

        $exists->delete();

        return $response->success( 'Catalog successfully removed from generic collection' );
    }
    
}
