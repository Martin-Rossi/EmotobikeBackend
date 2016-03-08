<?php

namespace App\Http\Controllers;

use App\Import;
use App\ImportLog;
use App\User;
use App\Object;
use App\Catalog;
use App\Collection;
use App\GenericCollection;
use App\Category;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImportController extends Controller {

    public function index() {
    	$data = [
    		'users' 				=> Import::where( 'foreign_type', '=', 'user' )->count(),
    		'objects'				=> Import::where( 'foreign_type', '=', 'object' )->count(),
    		'catalogs'				=> Import::where( 'foreign_type', '=', 'catalog' )->count(),
    		'collections' 			=> Import::where( 'foreign_type', '=', 'collection' )->count(),
    		'generic_collections'	=> Import::where( 'foreign_type', '=', 'generic_collection' )->count(),
    		'categories'			=> Import::where( 'foreign_type', '=', 'category' )->count(),
    	];

        return view(
            'import.index',
            [
            	'data' => $data
            ]
        );
    }

    public function users() {
        $uids = [];

        $imports = Import::select( 'foreign_id' )->where( 'foreign_type', '=', 'user' )->get();

        if ( sizeof( $imports ) > 0 )
            foreach ( $imports as $import )
                $uids[] = $import->foreign_id;

        $users = User::whereIn( 'id', $uids )->get();

    	return view(
            'import.users',
            [
                'users' => $users
            ]
        );
    }

    public function import_users( Request $request ) {
    	$mappings = [
    		0 => 'id',
    		1 => 'parent_id',
    		2 => 'group_id',
    		3 => 'tags',
    		4 => 'name',
    		5 => 'email',
    		6 => 'image',
    		7 => 'profile_name',
    		8 => 'profile_description',
    		9 => 'chat',
    		10 => 'noteworthy',
            11 => 'password'
    	];

    	return $this->import_entity( $request, '\App\User', $mappings );
    }

    public function objects() {
        $oids = [];

        $imports = Import::select( 'foreign_id' )->where( 'foreign_type', '=', 'object' )->get();

        if ( sizeof( $imports ) > 0 )
            foreach ( $imports as $import )
                $oids[] = $import->foreign_id;

        $objects = Object::whereIn( 'id', $oids )->get();

        return view(
            'import.objects',
            [
                'objects' => $objects
            ]
        );
    }

    public function import_objects( Request $request ) {
        $mappings = [
            0 => 'id',
            1 => 'catalog_id',
            2 => 'category_id',
            3 => 'type_id',
            4 => 'tags',
            5 => 'name',
            6 => 'sku',
            7 => 'description',
            8 => 'url',
            9 => 'image',
            10 => 'weight',
            11 => 'retail_price',
            12 => 'sale_price',
            13 => 'offer_value',
            14 => 'offer_url',
            15 => 'offer_description',
            16 => 'offer_start',
            17 => 'offer_stop',
            18 => 'prod_detail_url',
            19 => 'competitor_flag',
            20 => 'curated',
            21 => 'author'
        ];

        return $this->import_entity( $request, '\App\Object', $mappings );
    }

    public function catalogs() {
        $cids = [];

        $imports = Import::select( 'foreign_id' )->where( 'foreign_type', '=', 'catalog' )->get();

        if ( sizeof( $imports ) > 0 )
            foreach ( $imports as $import )
                $cids[] = $import->foreign_id;

        $catalogs = Catalog::whereIn( 'id', $cids )->get();

        return view(
            'import.catalogs',
            [
                'catalogs' => $catalogs
            ]
        );
    }

    public function import_catalogs( Request $request ) {
        $mappings = [
            0 => 'id',
            1 => 'category_id',
            2 => 'type_id',
            3 => 'tags',
            4 => 'name',
            5 => 'title',
            6 => 'description',
            7 => 'image',
            8 => 'publish',
            9 => 'trending',
            10 => 'popular',
            11 => 'chat',
            12 => 'author'
        ];

        return $this->import_entity( $request, '\App\Catalog', $mappings );
    }

    public function collections() {
        $cids = [];

        $imports = Import::select( 'foreign_id' )->where( 'foreign_type', '=', 'collection' )->get();

        if ( sizeof( $imports ) > 0 )
            foreach ( $imports as $import )
                $cids[] = $import->foreign_id;

        $collections = Collection::whereIn( 'id', $cids )->get();

        return view(
            'import.collections',
            [
                'collections' => $collections
            ]
        );
    }

    public function import_collections( Request $request ) {
        $mappings = [
            0 => 'id',
            1 => 'collection_id',
            2 => 'foreign_id',
            3 => 'foreign_type',
            4 => 'name',
            5 => 'author'
        ];

        return $this->import_entity( $request, '\App\Collection', $mappings );
    }

    public function generic_collections() {
        $cids = [];

        $imports = Import::select( 'foreign_id' )->where( 'foreign_type', '=', 'genericcollection' )->get();

        if ( sizeof( $imports ) > 0 )
            foreach ( $imports as $import )
                $cids[] = $import->foreign_id;

        $generic_collections = GenericCollection::whereIn( 'id', $cids )->get();

        return view(
            'import.generic_collections',
            [
                'generic_collections' => $generic_collections
            ]
        );
    }

    public function import_generic_collections( Request $request ) {
        $mappings = [
            0 => 'id',
            1 => 'collection_id',
            2 => 'foreign_id',
            3 => 'foreign_type',
            4 => 'name'
        ];

        return $this->import_entity( $request, '\App\GenericCollection', $mappings );
    }

    public function categories() {
        $cids = [];

        $imports = Import::select( 'foreign_id' )->where( 'foreign_type', '=', 'category' )->get();

        if ( sizeof( $imports ) > 0 )
            foreach ( $imports as $import )
                $cids[] = $import->foreign_id;

        $categories = Category::whereIn( 'id', $cids )->get();

        return view(
            'import.categories',
            [
                'categories' => $categories
            ]
        );
    }

    public function import_categories( Request $request ) {
        $mappings = [
            0 => 'id',
            1 => 'name'
        ];

        return $this->import_entity( $request, '\App\Category', $mappings );
    }

    public function import_entity( $request, $model, $mappings ) {
        $handle = $this->upload( $request );

        if ( is_object( $handle ) )
            return $handle;

        $entities = [];
        $row = 0;

        while ( ( $data = fgetcsv( $handle, 100000, ';' ) ) !== false ) {
            $row++;

            if ( $row > 1 ) {
                if ( is_array( $data ) && sizeof( $data ) > 0 ) {
                    $entity = [];

                    foreach ( $data as $key => $value ) {
                        if ( isset( $mappings[$key] ) && $value ) {
                            $entity[$mappings[$key]] = $value;
                        }
                    }

                    if ( sizeof( $entity ) )
                        $entities[] = $entity;
                }
            }
        }

        fclose( $handle );

        if ( ! sizeof( $entities ) > 0 )
            return back()->with( 'error', 'Nothing found for import' );

        $type = strtolower( substr( $model, 5 ) );

        foreach ( $entities as $entity ) {
            $obj = null;

            if ( isset( $entity['id'] ) && is_numeric( $entity['id'] ) )
              $obj = $model::find( $entity['id'] );

            if ( $obj ) {
                try {
                    $obj->update( $entity );
                } catch ( \Exception $e ) {
                    $this->error_log( strtolower( $type ), $entity, $e->getMessage() );
                }
            } else {
                try {
                    if ( ! isset( $entity['collection_id'] ) || ! $entity['collection_id'] || ! is_numeric( $entity['collection_id'] ) ) {
                        if ( 'collection' == $type || 'genericcollection' == $type ) {
                            if ( 'collection' == $type )
                                $last = $model::where( 'author', '=', $entity['author'] )->orderBy( 'collection_id', 'DESC' )->first();
                            else
                                $last = $model::orderBy( 'collection_id', 'DESC' )->first();

                            if ( ! $last )
                                $entity['collection_id'] = 1;
                            else
                                $entity['collection_id'] = $last->collection_id + 1;
                        }
                    }

                    $obj = $model::create( $entity );

                    if ( $obj->id )
                        $this->mark( $obj->id, strtolower( $type ) );
                } catch ( \Exception $e ) {
                    $this->error_log( strtolower( $type ), $entity, $e->getMessage() );
                }
            }
        }

        return back()->with( 'info', 'Import finished' );
    }

    private function upload( $request ) {
    	if ( ! $request->hasFile( 'csv_file' ) || ! $request->file( 'csv_file' )->isValid() )
            return back()->with( 'error', 'File upload failed' );

    	$file = $request->file( 'csv_file' );

    	if ( 'text/csv' != $file->getMimeType() && 'text/plain' != $file->getMimeType() )
            return back()->with( 'error', 'Not a valid CSV file' );

    	$file->move( storage_path() . '/uploads/', $file->getClientOriginalName() );

    	if ( ! is_file( storage_path() . '/uploads/' . $file->getClientOriginalName() ) )
            return back()->with( 'error', 'File upload failed' );

    	$handle = fopen( storage_path() . '/uploads/' . $file->getClientOriginalName(), 'r' );

    	return $handle;
    }

    private function mark( $id, $type ) {
        $import = new Import();

        $import->foreign_id = $id;
        $import->foreign_type = $type;

        $import->save();
    }

    private function error_log( $type, $object, $message ) {
        $log = new ImportLog();
        
        $log->log = 'error';
        $log->type = $type;
        $log->object = serialize( $object );
        $log->message = $message;

        $log->save();
    }

}
