<?php

namespace App\Http\Controllers;

use App\Object;
use App\Category;
use App\Type;
use App\Comment;
use App\Like;
use App\Follow;
use App\Recommendation;
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
                            ->with( 'current_user_like' )
                            ->paginate( $this->pp );

        return $response->result( $objects->toArray() );
    }

    public function show( $id, ApiResponse $response ) {
        $object = Object::where( 'id', '=', $id )
                        ->with( 'catalog','type','author','current_user_like' )
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

        auth()->user()->count_authored++;
        auth()->user()->save();

        return $response->success( 'Object created successfully' );
    }

    public function update( $id, Request $request, ApiResponse $response ) {
        $object = Object::where( 'id', '=', $id )
                        ->first();

        if ( is_null( $object ) )
            abort( 404 );

        if ( ! $this->canTouch( $object ) )
            abort( 403 );

        $inputs = $request->all();

        unset( $inputs['author'] );

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
                        ->first();

        if ( is_null( $object ) )
            abort( 404 );

        if ( ! $this->canTouch( $object ) )
            abort( 403 );

        $object->status = -1;
        $object->save();

        return $response->success( 'Object succesfully deleted' );
    }

    public function deleted( ApiResponse $response ) {
        $user = auth()->user();

        $user_ids = [$user->id];

        $parent = $user->parent();

        if ( $parent )
            $user_ids[] = $parent->id;

        $children = $user->children();

        if ( sizeof( $children ) > 0 )
            foreach ( $children as $child )
                $user_ids[] = $child->id;

        $objects = Object::where( 'status', '<', 0 )
                         ->whereIn( 'author', $user_ids )
                         ->paginate( $this->pp );

        return $response->result( $objects->toArray() );
    }

    public function search( Request $request, ApiResponse $response ) {
        $objects = Object::where( 'status', '>', 0 )
                         ->where( 'name', 'LIKE', '%' . $request->get( 'term' ) . '%' )
                         ->orWhere( 'description', 'LIKE', '%' . $request->get( 'term' ) . '%' )
                         ->with( 'catalog', 'category', 'type', 'author' ,'current_user_like')
                         ->paginate( $this->pp );

        return $response->result( $objects->toArray() );
    }

    public function filter( Request $request, ApiResponse $response ) {
        $objects = [];

        $operators = [
            '=',
            '<',
            '>',
            'LIKE'
        ];

        $operator = $request->get( 'operator' );

        if ( ! in_array( $operator, $operators ) )
            return $response->result( $objects );

        $filters = [
            'catalog_id',
            'category_id',
            'type_id',
            'tags',
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
                         ->with( 'catalog', 'category', 'type', 'author','current_user_like' )
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

        auth()->user()->count_likes++;
        auth()->user()->save();

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
    public function unlike( $id, ApiResponse $response ) {

        $object = Object::find( $id );

        if ( is_null( $object ) )
            abort( 404 );

        $exists = Like::where( 'foreign_id', '=', $id )
            ->where( 'foreign_type', '=', 'object' )
            ->where( 'author', '=', auth()->user()->id )
            ->first();

        if ( !$exists )
            return $response->error( 'This object doesn`t have a like.' );

        try {
            Like::destroy($exists->id);
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        auth()->user()->count_likes--;
        auth()->user()->save();

        $object->count_likes--;
        $object->save();

        return $response->success( 'Like deleted successfully' );
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

        auth()->user()->count_following++;
        auth()->user()->save();

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

    public function recommend( $id, ApiResponse $response ) {
        $object = Object::find( $id );

        if ( is_null( $object ) )
            abort( 404 );

        $exists = Recommendation::where( 'foreign_id', '=', $id )
                                ->where( 'foreign_type', '=', 'object' )
                                ->where( 'author', '=', auth()->user()->id )
                                ->first();

        if ( $exists )
            return $response->error( 'This user already recommended this object' );

        $recommendation = [
            'foreign_id'    => $id,
            'foreign_type'  => 'object',
            'author'        => auth()->user()->id
        ];

        try {
            Recommendation::create( $recommendation );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        $object->count_recommended++;
        $object->save();

        return $response->success( 'Recommendation created successfully' );
    }

    public function recommendations( $id, ApiResponse $response ) {
        $object = Object::find( $id );

        if ( is_null( $object ) )
            abort( 404 );

        return $response->result( $object->recommendations() );
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

    public function positions( Request $request, ApiResponse $response){
        $id = $request->get( 'id' );
        $objects = Object::where( 'status', '>', 0 )
            ->orderBy('position', 'asc')
            ->groupBy('position')
            ->with( 'catalog', 'category', 'type', 'author','current_user_like' )
            ->take(19);
        if( !empty( $id ) ){
            $objects = $objects->where( 'catalog_id', '=', $id );
        }
        return $response->result( $objects->get() );
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

    private function canTouch( $object ) {
        if ( auth()->user()->group_id <= 2 )
            return true;
        
        if ( $object->author == auth()->user()->id )
            return true;

        if ( $object->author == auth()->user()->parent_id )
            return true;

        $children = auth()->user()->children();

        if ( sizeof( $children ) > 0 ) {
            foreach ( $children as $child )
                if ( $object->author == $child->id )
                    return true;
        }

        return false;
    }
    
}
