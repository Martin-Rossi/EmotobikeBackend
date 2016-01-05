<?php

namespace App\Http\Controllers;

use App\Catalog;
use App\Category;
use App\Type;
use App\Comment;
use App\Like;
use App\Follow;
use App\Feedback;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class CatalogController extends Controller {

    protected $pp = 10;

    public function __construct( Request $request ) {
        if ( $request->get( 'pp' ) )
            $this->pp = intval( $request->get( 'pp' ) );
    }

    public function index( ApiResponse $response ) {
        $catalogs = Catalog::where( 'status', '>', 0 )
                           ->paginate( $this->pp );

        return $response->result( $catalogs->toArray() );
    }

    public function show( $id, ApiResponse $response ) {
        $catalog = Catalog::where( 'id', '=', $id )
                          ->with( 'type' )
                          ->with( 'author' )
                          ->first();

        if ( is_null( $catalog ) )
            abort( 404 );

        return $response->result( $catalog );
    }

    public function store( Request $request, ApiResponse $response ) {
        $inputs = $request->all();

        $inputs['author'] = auth()->user()->id;

        $inputs = $this->assignCategory( $inputs );
        $inputs = $this->assignType( $inputs );

        try {
            Catalog::create( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Catalog created successfully' );
    }

    public function update( $id, Request $request, ApiResponse $response ) {
        $catalog = Catalog::where( 'id', '=', $id )
                          ->where( 'author', '=', auth()->user()->id )
                          ->first();

        if ( is_null( $catalog ) )
            abort( 404 );

        $inputs = $request->all();

        $inputs['author'] = auth()->user()->id;

        $inputs = $this->assignCategory( $inputs );
        $inputs = $this->assignType( $inputs );

        try {
            $catalog->update( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Catalog updated successfully' );
    }

    public function destroy( $id, ApiResponse $response ) {
        $catalog = Catalog::where( 'id', '=', $id )
                          ->where( 'author', '=', auth()->user()->id )
                          ->first();

        if ( is_null( $catalog ) )
            abort( 404 );

        $catalog->status = -1;
        $catalog->save();

        return $response->success( 'Catalog succesfully deleted' );
    }

    public function deleted( ApiResponse $response ) {
        $catalogs = Catalog::where( 'status', '<', 0 )
                           ->where( 'author', '=', auth()->user()->id )
                           ->paginate( $this->pp );

        return $response->result( $catalogs->toArray() );
    }

    public function search( Request $request, ApiResponse $response ) {
        $catalogs = Catalog::where( 'status', '>', 0 )
                           ->where( 'name', 'LIKE', '%' . $request->get( 'term' ) . '%' )
                           ->orWhere( 'title', 'LIKE', '%' . $request->get( 'term' ) . '%' )
                           ->orWhere( 'description', 'LIKE', '%' . $request->get( 'term' ) . '%' )
                           ->with( 'category', 'type', 'objects', 'author' )
                           ->paginate( $this->pp );

        return $response->result( $catalogs->toArray() );
    }

    public function filter( Request $request, ApiResponse $response ) {
        $catalogs = [];

        $operators = [
            '=',
            '<',
            '>'
        ];

        $operator = $request->get( 'operator' );

        if ( ! in_array( $operator, $operators ) )
            return $response->result( $catalogs );

        $filters = [
            'category_id',
            'type_id',
            'author',
            'created_at',
            'updated_at'
        ];

        $filter = $request->get( 'filter' );

        if ( ! in_array( $filter, $filters ) )
            return $response->result( $catalogs );

        $catalogs = Catalog::where( 'status', '>', 0 )
                           ->where( $filter, $operator, $request->get( 'value' ) )
                           ->with( 'category', 'type', 'objects', 'author' )
                           ->paginate( $this->pp );

        return $response->result( $catalogs->toArray() );
    }

    public function objects( $id, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        return $response->result( $catalog->objects );
    }

    public function products( $id, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        return $response->result( $catalog->products() );
    }

    public function contents( $id, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        return $response->result( $catalog, $catalog->objects );
    }

    public function comment( $id, Request $request, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        $comment = [
            'foreign_id'    => $id,
            'foreign_type'  => 'catalog',
            'text'          => $request->get( 'text' ),
            'author'        => auth()->user()->id
        ];

        try {
            Comment::create( $comment );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        $catalog->count_comments++;
        $catalog->save();

        return $response->success( 'Comment recorded successfully' );
    }

    public function comments( $id, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        return $response->result( $catalog->comments() );
    }

    public function like( $id, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        $exists = Like::where( 'foreign_id', '=', $id )
                      ->where( 'foreign_type', '=', 'catalog' )
                      ->where( 'author', '=', auth()->user()->id )
                      ->first();

        if ( $exists )
            return $response->error( 'This user already liked this catalog' );

        $like = [
            'foreign_id'    => $id,
            'foreign_type'  => 'catalog',
            'author'        => auth()->user()->id
        ];

        try {
            Like::create( $like );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        $catalog->count_likes++;
        $catalog->save();

        return $response->success( 'Like recorded successfully' );
    }

    public function likes( $id, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        return $response->result( $catalog->likes() );
    }

    public function follow( $id, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        $exists = Follow::where( 'foreign_id', '=', $id )
                        ->where( 'foreign_type', '=', 'catalog' )
                        ->where( 'author', '=', auth()->user()->id )
                        ->first();

        if ( $exists )
            return $response->error( 'This user already followed this catalog' );

        $follow = [
            'foreign_id'    => $id,
            'foreign_type'  => 'catalog',
            'author'        => auth()->user()->id
        ];

        try {
            Follow::create( $follow );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        $catalog->count_follows++;
        $catalog->save();

        return $response->success( 'Follow recorded successfully' );
    }

    public function follows( $id, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        return $response->result( $catalog->follows() );
    }

    public function feedback( $id, Request $request, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        $feedback = [
            'foreign_id'    => $id,
            'foreign_type'  => 'catalog',
            'value'         => $request->get( 'value' ),
            'author'        => auth()->user()->id
        ];

        try {
            Feedback::create( $feedback );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Feedback recorded successfully' );
    }

    public function feedbacks( $id, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        return $response->result( $catalog->feedbacks() );
    }

    public function activities( $id, ApiResponse $response ) {
        $catalog = Catalog::find( $id );

        if ( is_null( $catalog ) )
            abort( 404 );

        return $response->result( $catalog->activities );
    }

    private function assignCategory( $inputs ) {
        if ( isset( $inputs['category'] ) && $inputs['category'] ) {
            if ( is_numeric( $inputs['category'] ) )
                $inputs['category_id'] = $inputs['category'];
            else {
                $category = Category::where( 'name', '=', $inputs['category'] )->first();

                if ( $category )
                    $inputs['category_id'] = $category->id;
                else {
                    $category = new Category();
                    $category->name = $inputs['category'];

                    $category->save();

                    $inputs['category_id'] = $category->id;
                }
            }
        }

        return $inputs;
    }

    private function assignType( $inputs ) {
        if ( isset( $inputs['type'] ) && $inputs['type'] ) {
            if ( is_numeric( $inputs['type'] ) )
                $inputs['type_id'] = $inputs['type'];
            else {
                $type = Type::where( 'name', '=', $inputs['type'] )->first();

                if ( $type )
                    $inputs['type_id'] = $type->id;
                else {
                    $type = new Type();
                    $type->name = $inputs['type'];

                    $type->save();

                    $inputs['type_id'] = $type->id;
                }
            }
        }

        return $inputs;
    }
    
}
