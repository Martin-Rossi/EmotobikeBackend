<?php

namespace App\Http\Controllers;

use App\Object;
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

class ObjectController extends Controller {

    protected $pp = 10;

    public function __construct( Request $request ) {
        if ( $request->get( 'pp' ) )
            $this->pp = intval( $request->get( 'pp' ) );
    }

    public function index( ApiResponse $response ) {
        $objects = Object::where( 'status', '>', 0 )
                         ->paginate( $this->pp );

        return $response->result( $objects->toArray() );
    }

    public function show( $id, ApiResponse $response ) {
        $object = Object::where( 'id', '=', $id )
                        ->with( 'catalog' )
                        ->with( 'type' )
                        ->with( 'author' )
                        ->first();

        if ( is_null( $object ) )
            abort( 404 );

        $pprice = $object->personal_price();
        $object->pprice = $pprice;

        return $response->result( $object );
    }

    public function store( Request $request, ApiResponse $response ) {
        $inputs = $request->all();

        $inputs['author'] = auth()->user()->id;

        $inputs = $this->assignCategory( $inputs );
        $inputs = $this->assignType( $inputs );

        try {
            Object::create( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Object created successfully' );
    }

    public function update( $id, Request $request, ApiResponse $response ) {
        $object = Object::where( 'id', '=', $id )
                        ->where( 'author', '=', auth()->user()->id )
                        ->first();

        if ( is_null( $object ) )
            abort( 404 );

        $inputs = $request->all();

        $inputs['author'] = auth()->user()->id;

        $inputs = $this->assignCategory( $inputs );
        $inputs = $this->assignType( $inputs );

        try {
            $object->update( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Object updated successfully' );
    }

    public function destroy( $id, ApiResponse $response ) {
        $object = Object::where( 'id', '=', $id )
                        ->where( 'author', '=', auth()->user()->id )
                        ->first();

        if ( is_null( $object ) )
            abort( 404 );

        $object->status = -1;
        $object->save();

        return $response->success( 'Object succesfully deleted' );
    }

    public function deleted( ApiResponse $response ) {
        $objects = Object::where( 'status', '<', 0 )
                         ->where( 'author', '=', auth()->user()->id )
                         ->paginate( $this->pp );

        return $response->result( $objects->toArray() );
    }

    public function search( Request $request, ApiResponse $response ) {
        $objects = Object::where( 'status', '>', 0 )
                         ->where( 'name', 'LIKE', '%' . $request->get( 'term' ) . '%' )
                         ->orWhere( 'description', 'LIKE', '%' . $request->get( 'term' ) . '%' )
                         ->with( 'catalog', 'category', 'type', 'author' )
                         ->paginate( $this->pp );

        return $response->result( $objects->toArray() );
    }

    public function filter( Request $request, ApiResponse $response ) {
        $objects = [];

        $operators = [
            '=',
            '<',
            '>'
        ];

        $operator = $request->get( 'operator' );

        if ( ! in_array( $operator, $operators ) )
            return $response->result( $objects );

        $filters = [
            'catalog_id',
            'category_id',
            'type_id',
            'retail_price',
            'sale_price',
            'layout',
            'position',
            'competitor_flag',
            'recomended',
            'curated',
            'author',
            'created_at',
            'updated_at'
        ];

        $filter = $request->get( 'filter' );

        if ( ! in_array( $filter, $filters ) )
            return $response->result( $objects );

        $objects = Object::where( 'status', '>', 0 )
                         ->where( $filter, $operator, $request->get( 'value' ) )
                         ->with( 'catalog', 'category', 'type', 'author' )
                         ->paginate( $this->pp );

        return $response->result( $objects->toArray() );
    }

    public function catalog( $id, ApiResponse $response ) {
        $object = Object::find( $id );

        if ( is_null( $object ) )
            abort( 404 );

        return $response->result( $object->catalog );
    }

    public function comment( $id, Request $request, ApiResponse $response ) {
        $object = Object::find( $id );

        if ( is_null( $object ) )
            abort( 404 );

        $comment = [
            'foreign_id'    => $id,
            'foreign_type'  => 'object',
            'text'          => $request->get( 'text' ),
            'author'        => auth()->user()->id
        ];

        try {
            Comment::create( $comment );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        $object->count_comments++;
        $object->save();

        return $response->success( 'Comment recorded successfully' );
    }

    public function comments( $id, ApiResponse $response ) {
        $object = Object::find( $id );

        if ( is_null( $object ) )
            abort( 404 );

        return $response->result( $object->comments() );
    }

    public function like( $id, ApiResponse $response ) {
        $object = Object::find( $id );

        if ( is_null( $object ) )
            abort( 404 );

        $exists = Like::where( 'foreign_id', '=', $id )
                      ->where( 'foreign_type', '=', 'object' )
                      ->where( 'author', '=', auth()->user()->id )
                      ->first();

        if ( $exists )
            return $response->error( 'This user already liked this object' );

        $like = [
            'foreign_id'    => $id,
            'foreign_type'  => 'object',
            'author'        => auth()->user()->id
        ];

        try {
            Like::create( $like );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        $object->count_likes++;
        $object->save();

        return $response->success( 'Like recorded successfully' );
    }

    public function likes( $id, ApiResponse $response ) {
        $object = Object::find( $id );

        if ( is_null( $object ) )
            abort( 404 );

        return $response->result( $object->likes() );
    }

    public function follow( $id, ApiResponse $response ) {
        $object = Object::find( $id );

        if ( is_null( $object ) )
            abort( 404 );

        $exists = Follow::where( 'foreign_id', '=', $id )
                        ->where( 'foreign_type', '=', 'object' )
                        ->where( 'author', '=', auth()->user()->id )
                        ->first();

        if ( $exists )
            return $response->error( 'This user already followed this object' );

        $follow = [
            'foreign_id'    => $id,
            'foreign_type'  => 'object',
            'author'        => auth()->user()->id
        ];

        try {
            Follow::create( $follow );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        $object->count_follows++;
        $object->save();

        return $response->success( 'Follow recorded successfully' );
    }

    public function follows( $id, ApiResponse $response ) {
        $object = Object::find( $id );

        if ( is_null( $object ) )
            abort( 404 );

        return $response->result( $object->follows() );
    }

    public function feedback( $id, Request $request, ApiResponse $response ) {
        $object = Object::find( $id );

        if ( is_null( $object ) )
            abort( 404 );

        $feedback = [
            'foreign_id'    => $id,
            'foreign_type'  => 'object',
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
        $object = Object::find( $id );

        if ( is_null( $object ) )
            abort( 404 );

        return $response->result( $object->feedbacks() );
    }

    public function positions( $id, Request $request, ApiResponse $response ) {
        
        $objects = Object::where( 'status', '>', 0 )
                         ->where( 'catalog_id', '=', $id )
                         ->orderBy( 'position', 'asc' )
                         ->groupBy( 'position' )
                         ->with( 'catalog', 'category', 'type', 'author' )
                         ->take( 19 )
                         ->get();
                         
        return $response->result( $objects );
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
