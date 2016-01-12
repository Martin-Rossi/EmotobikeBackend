<?php

namespace App\Http\Controllers;

use App\Collection;
use App\Object;
use App\Catalog;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class CollectionController extends Controller {

    protected $pp = 10;

    public function __construct( Request $request ) {
        if ( $request->get( 'pp' ) )
            $this->pp = intval( $request->get( 'pp' ) );
    }

    public function index( ApiResponse $response ) {
        $collections = Collection::where( 'status', '>', 0 )
                                 ->where( 'author', '=', auth()->user()->id )
                                 ->paginate( $this->pp );

        $i = 0;

        foreach ( $collections as $collection ) {
            if ( 'object' == $collection->foreign_type )
                $collections[$i]->object = Object::find( $collection->foreign_id )->toArray();
            else
                $collections[$i]->catalog = Catalog::find( $collection->foreign_id )->toArray();


            $i++;
        }

        return $response->result( $collections->toArray() );
    }

    public function show( $id, ApiResponse $response ) {
        $collection = Collection::where( 'collection_id', '=', $id )
                                ->where( 'author', '=', auth()->user()->id )
                                ->get();

        return $response->result( $collection->toArray() );
    }

    public function store( Request $request, ApiResponse $response ) {
        $inputs = $request->all();

        $inputs['author'] = auth()->user()->id;

        if ( 'catalog' != $inputs['foreign_type'] && 'object' != $inputs['foreign_type'] )
            return $response->error( 'Invalid foreign type specified' );

        $last = Collection::where( 'author', '=', auth()->user()->id )
                          ->orderBy( 'collection_id', 'DESC' )
                          ->first();

        if ( ! $last )
            $inputs['collection_id'] = 1;
        else {
            $collection_id = $last->collection_id + 1;

            $inputs['collection_id'] = $collection_id;
        }

        try {
            Collection::create( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Collection created successfully' );
    }

    public function destroy( $id, ApiResponse $response ) {
        $collection = Collection::where( 'collection_id', '=', $id )
                                ->where( 'author', '=', auth()->user()->id )
                                ->get();

        if ( ! sizeof( $collection ) > 0 )
            abort( 404 );

        foreach ( $collection as $c ) {
            $c->status = -1;
            $c->save();
        }

        return $response->success( 'Collection succesfully deleted' );
    }

    public function deleted( ApiResponse $response ) {
        $collections = Collection::where( 'status', '<', 0 )
                                 ->where( 'author', '=', auth()->user()->id )
                                 ->paginate( $this->pp );

        $i = 0;
        
        foreach ( $collections as $collection ) {
            if ( 'object' == $collection->foreign_type )
                $collections[$i]->object = Object::find( $collection->foreign_id )->toArray();
            else
                $collections[$i]->catalog = Catalog::find( $collection->foreign_id )->toArray();


            $i++;
        }

        return $response->result( $collections->toArray() );
    }

    public function objects( $id, ApiResponse $response ) {
        $collection = Collection::where( 'collection_id', '=', $id )
                                ->where( 'foreign_type', '=', 'object' )
                                ->where( 'author', '=', auth()->user()->id )
                                ->get();

        $object_ids = [];

        if ( ! sizeof( $collection ) > 0 )
            return $response->result( $object_ids );

        foreach ( $collection as $object )
            $object_ids[] = $object->foreign_id;

        $objects = Object::whereIn( 'id', $object_ids )->with( 'catalog' )->get();

        return $response->result( $objects );
    }

    public function catalogs( $id, ApiResponse $response ) {
        $collection = Collection::where( 'collection_id', '=', $id )
                                ->where( 'foreign_type', '=', 'catalog' )
                                ->where( 'author', '=', auth()->user()->id )
                                ->get();

        $catalog_ids = [];

        if ( ! sizeof( $collection ) > 0 )
            return $response->result( $catalog_ids );

        foreach ( $collection as $catalog )
            $catalog_ids[] = $catalog->foreign_id;

        $catalogs = Catalog::whereIn( 'id', $catalog_ids )->with( 'objects' )->get();

        return $response->result( $catalogs );
    }

    public function addObject( $id, Request $request, ApiResponse $response ) {
        $inputs = $request->all();

        if ( ! isset( $inputs['object_id'] ) )
            return $response->error( 'Please specify an object id' );

        if ( ! $inputs['object_id'] )
            return $response->error( 'Please specify an object id' );

        if ( ! is_numeric( $inputs['object_id'] ) )
            return $response->error( 'Please specify an object id' );

        $exists = Collection::where( 'collection_id', '=', $id )
                            ->where( 'foreign_id', '=', $inputs['object_id'] )
                            ->where( 'foreign_type', '=', 'object' )
                            ->where( 'author', '=', auth()->user()->id )
                            ->first();

        if ( $exists )
            return $response->error( 'Object already added to this collection' );

        $collection = new Collection();
        $collection->collection_id = $id;
        $collection->foreign_id = $inputs['object_id'];
        $collection->foreign_type = 'object';
        $collection->author = auth()->user()->id;

        $collection->save();

        return $response->success( 'Object successfully added to collection' );
    }

    public function addCatalog( $id, Request $request, ApiResponse $response ) {
        $inputs = $request->all();

        if ( ! isset( $inputs['catalog_id'] ) )
            return $response->error( 'Please specify a catalog id' );

        if ( ! $inputs['catalog_id'] )
            return $response->error( 'Please specify a catalog id' );

        if ( ! is_numeric( $inputs['catalog_id'] ) )
            return $response->error( 'Please specify a catalog id' );

        $exists = Collection::where( 'collection_id', '=', $id )
                            ->where( 'foreign_id', '=', $inputs['catalog_id'] )
                            ->where( 'foreign_type', '=', 'catalog' )
                            ->where( 'author', '=', auth()->user()->id )
                            ->first();

        if ( $exists )
            return $response->error( 'Catalog already added to this collection' );

        $collection = new Collection();
        $collection->collection_id = $id;
        $collection->foreign_id = $inputs['catalog_id'];
        $collection->foreign_type = 'catalog';
        $collection->author = auth()->user()->id;

        $collection->save();

        return $response->success( 'Catalog successfully added to collection' );
    }

    public function removeObject( $id, Request $request, ApiResponse $response ) {
        $inputs = $request->all();

        if ( ! isset( $inputs['object_id'] ) )
            return $response->error( 'Please specify an object id' );

        if ( ! $inputs['object_id'] )
            return $response->error( 'Please specify an object id' );

        if ( ! is_numeric( $inputs['object_id'] ) )
            return $response->error( 'Please specify an object id' );

        $exists = Collection::where( 'collection_id', '=', $id )
                            ->where( 'foreign_id', '=', $inputs['object_id'] )
                            ->where( 'foreign_type', '=', 'object' )
                            ->where( 'author', '=', auth()->user()->id )
                            ->first();

        if ( ! $exists )
            return $response->error( 'Object not in this collection' );

        $exists->delete();

        return $response->success( 'Object successfully removed from collection' );
    }

    public function removeCatalog( $id, Request $request, ApiResponse $response ) {
        $inputs = $request->all();

        if ( ! isset( $inputs['catalog_id'] ) )
            return $response->error( 'Please specify a catalog id' );

        if ( ! $inputs['catalog_id'] )
            return $response->error( 'Please specify a catalog id' );

        if ( ! is_numeric( $inputs['catalog_id'] ) )
            return $response->error( 'Please specify a catalog id' );

        $exists = Collection::where( 'collection_id', '=', $id )
                            ->where( 'foreign_id', '=', $inputs['catalog_id'] )
                            ->where( 'foreign_type', '=', 'catalog' )
                            ->where( 'author', '=', auth()->user()->id )
                            ->first();

        if ( ! $exists )
            return $response->error( 'Catalog not in this collection' );

        $exists->delete();

        return $response->success( 'Catalog successfully removed from collection' );
    }
    
}
