<?php

namespace App\Http\Controllers;

use App\Import;
use App\User;
use App\Object;
use App\Catalog;
use App\Collection;
use App\GenericCollection;
use App\Category;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExportController extends Controller {

    public function users() {
        $csv = \League\Csv\Writer::createFromFileObject( new \SplTempFileObject() );

        $csv->setDelimiter( ';' );

        $csv->insertOne( [
            'ID',
            'Parent ID',
            'Group ID',
            'Tags',
            'Name',
            'Email',
            'Password',
            'Image',
            'Profile Name',
            'Profile Description',
            'Chat',
            'Noteworthy'
        ] );

        $imports = Import::select( 'foreign_id' )->where( 'foreign_type', '=', 'user' )->get();

        $uids = [];

        if ( sizeof( $imports ) > 0 )
            foreach ( $imports as $import )
                $uids[] = $import->foreign_id;

        $users = User::select( 'id', 'parent_id', 'group_id', 'tags', 'name', 'email', 'trend', 'image', 'profile_name', 'profile_description', 'chat', 'noteworthy' )->whereIn( 'id', $uids )->get();

        if ( sizeof( $users ) > 0 ) {
            foreach ( $users as $user ) {
                $ins = $user->toArray();
                $ins['trend'] = '*****';

                $csv->insertOne( $ins );
            }
        }

        $csv->output( 'users.csv' );
    }

    public function objects() {
        $csv = \League\Csv\Writer::createFromFileObject( new \SplTempFileObject() );

        $csv->setDelimiter( ';' );

        $csv->insertOne( [
            'ID',
            'Catalog ID',
            'Category ID',
            'Type ID',
            'Tags',
            'Name',
            'SKU',
            'Description',
            'URL',
            'Image',
            'Weight',
            'Retail Price',
            'Sale Price',
            'Offer Value',
            'Offer URL',
            'Offer Description',
            'Offer Start',
            'Offer Stop',
            'Product Detail URL',
            'Competitor Flag',
            'Curated',
            'Author'
        ] );

        $imports = Import::select( 'foreign_id' )->where( 'foreign_type', '=', 'object' )->get();

        $oids = [];

        if ( sizeof( $imports ) > 0 )
            foreach ( $imports as $import )
                $oids[] = $import->foreign_id;

        $objects = Object::select( 'id', 'catalog_id', 'category_id', 'type_id', 'tags', 'name', 'sku', 'description', 'url', 'image', 'weight', 'retail_price', 'sale_price', 'offer_value', 'offer_url', 'offer_description', 'offer_start', 'offer_stop', 'prod_detail_url', 'competitor_flag', 'curated', 'author' )->whereIn( 'id', $oids )->get();

        if ( sizeof( $objects ) > 0 )
            foreach ( $objects as $object )
                $csv->insertOne( $object->toArray() );

        $csv->output( 'objects.csv' );
    }

    public function catalogs() {
        $csv = \League\Csv\Writer::createFromFileObject( new \SplTempFileObject() );

        $csv->setDelimiter( ';' );

        $csv->insertOne( [
            'ID',
            'Category ID',
            'Type ID',
            'Tags',
            'Name',
            'Title',
            'Description',
            'Image',
            'Publish',
            'Trending',
            'Popular',
            'Chat',
            'Author'
        ] );

        $imports = Import::select( 'foreign_id' )->where( 'foreign_type', '=', 'catalog' )->get();

        $cids = [];

        if ( sizeof( $imports ) > 0 )
            foreach ( $imports as $import )
                $cids[] = $import->foreign_id;

        $catalogs = Catalog::select( 'id', 'category_id', 'type_id', 'tags', 'name', 'title', 'description', 'image', 'publish', 'trending', 'popular', 'chat', 'author' )->whereIn( 'id', $cids )->get();

        if ( sizeof( $catalogs ) > 0 )
            foreach ( $catalogs as $catalog )
                $csv->insertOne( $catalog->toArray() );

        $csv->output( 'catalogs.csv' );
    }

    public function collections() {
        $csv = \League\Csv\Writer::createFromFileObject( new \SplTempFileObject() );

        $csv->setDelimiter( ';' );

        $csv->insertOne( [
            'ID',
            'Collection ID',
            'Foreign ID',
            'Foreign Type',
            'Name',
            'Author'
        ] );

        $imports = Import::select( 'foreign_id' )->where( 'foreign_type', '=', 'collection' )->get();

        $cids = [];

        if ( sizeof( $imports ) > 0 )
            foreach ( $imports as $import )
                $cids[] = $import->foreign_id;

        $collections = Collection::select( 'id', 'collection_id', 'foreign_id', 'foreign_type', 'name', 'author' )->whereIn( 'id', $cids )->get();

        if ( sizeof( $collections ) > 0 )
            foreach ( $collections as $collection )
                $csv->insertOne( $collection->toArray() );

        $csv->output( 'collections.csv' );
    }

    public function generic_collections() {
        $csv = \League\Csv\Writer::createFromFileObject( new \SplTempFileObject() );

        $csv->setDelimiter( ';' );

        $csv->insertOne( [
            'ID',
            'Collection ID',
            'Foreign ID',
            'Foreign Type',
            'Name'
        ] );

        $imports = Import::select( 'foreign_id' )->where( 'foreign_type', '=', 'genericcollection' )->get();

        $cids = [];

        if ( sizeof( $imports ) > 0 )
            foreach ( $imports as $import )
                $cids[] = $import->foreign_id;

        $generic_collections = GenericCollection::select( 'id', 'collection_id', 'foreign_id', 'foreign_type', 'name' )->whereIn( 'id', $cids )->get();

        if ( sizeof( $generic_collections ) > 0 )
            foreach ( $generic_collections as $generic_collection )
                $csv->insertOne( $generic_collection->toArray() );

        $csv->output( 'generic_collections.csv' );
    }

    public function categories() {
        $csv = \League\Csv\Writer::createFromFileObject( new \SplTempFileObject() );

        $csv->setDelimiter( ';' );

        $csv->insertOne( [
            'ID',
            'Name'
        ] );

        $imports = Import::select( 'foreign_id' )->where( 'foreign_type', '=', 'category' )->get();

        $cids = [];

        if ( sizeof( $imports ) > 0 )
            foreach ( $imports as $import )
                $cids[] = $import->foreign_id;

        $categories = Category::select( 'id', 'name' )->whereIn( 'id', $cids )->get();

        if ( sizeof( $categories ) > 0 )
            foreach ( $categories as $category )
                $csv->insertOne( $category->toArray() );

        $csv->output( 'categories.csv' );
    }

}
