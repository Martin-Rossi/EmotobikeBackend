<?php

namespace App\Http\Controllers;

use Mail;
use Validator;
use App\User;
use App\Object;
use App\Catalog;
use App\Follow;
use App\Friend;
use App\Message;
use App\UserPreference;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class UserController extends Controller {

    protected $pp = 10;

    public function __construct( Request $request ) {
        if ( $request->get( 'pp' ) )
            $this->pp = intval( $request->get( 'pp' ) );
    }

    public function index( ApiResponse $response ) {
        $users = User::paginate( $this->pp );

        return $response->result( $users->toArray() );
    }

    public function show( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        return $response->result( $user );
    }

    public function store( Request $request, ApiResponse $response ) {
        $inputs = $request->all();

        $validator = Validator::make( $inputs, [
            'email'         => 'required|email|unique:users|max:255',
            'password'      => 'required|min:5|max:60',
            'name'          => 'required|max:255'
        ] );

        if ( $validator->fails() )
            return $response->error( $validator->errors()->first() );

        $inputs['password'] = bcrypt( $inputs['password'] );

        if ( auth()->user()->group_id > 2 )
            unset( $inputs['noteworthy'] );

        try {
            $user  = User::create( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        if ( auth()->user()->group_id > 2 ) {
            $user->parent_id = auth()->user()->id;
            $user->save();
        }

        return $response->success( 'User created successfully' );
    }

    public function update( $id, Request $request, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        if ( $user->id != auth()->user()->id )
            abort( 401 );

        $inputs = $request->all();

        if ( auth()->user()->group_id > 2 )
            unset( $inputs['noteworthy'] );

        try {
            $user->update( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'User updated successfully' );
    }

    public function search( Request $request, ApiResponse $response ) {
        $users = User::where( 'tags', 'LIKE', '%' . $request->get( 'term' ) . '%' )
                     ->orWhere( 'name', 'LIKE', '%' . $request->get( 'term' ) . '%' )
                     ->paginate( $this->pp );

        return $response->result( $users->toArray() );
    }

    public function getByField( Request $request, ApiResponse $response ) {

        $users = User::where( $request->get( 'field' ), '=',  $request->get( 'term' ) )->get();

        return $response->result( $users );
    }

    public function filter( Request $request, ApiResponse $response ) {
        $users = [];

        $operators = [
            '=',
            '<',
            '>'
        ];

        $operator = $request->get( 'operator' );

        if ( ! in_array( $operator, $operators ) )
            return $response->result( $users );

        $filters = [
            'created_at',
            'updated_at'
        ];

        $filter = $request->get( 'filter' );

        if ( ! in_array( $filter, $filters ) )
            return $response->result( $users );

        $users = User::where( $filter, $operator, $request->get( 'value' ) )
                     ->paginate( $this->pp );

        return $response->result( $users->toArray() );
    }

    public function objects( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        return $response->result( $user->objects );
    }

    public function catalogs( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        return $response->result( $user->catalogs );
    }

    public function collections( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        return $response->result( $user->collections );
    }

    public function comments( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        return $response->result( $user->comments );
    }

    public function likes( $id, ApiResponse $response ) {
        $entities = [];

        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        $likes = $user->likes;

        if ( ! sizeof( $likes ) > 0 )
            return $response->result( $entities );

        foreach ( $likes as $like ) {
            if ( 'catalog' == $like->foreign_type )
                $like->entity = Catalog::find( $like->foreign_id );
            else
                $like->entity = Object::find( $like->foreign_id );

            $entities[] = $like;
        }


        return $response->result( $entities );
    }

    public function following( $id, ApiResponse $response ) {
        $entities = [];

        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        $followings = $user->following;

        if ( ! sizeof( $followings ) > 0 )
            return $response->result( $entities );

        foreach ( $followings as $following ) {
            if ( 'catalog' == $following->foreign_type )
                $following->entity = Catalog::find( $following->foreign_id );
            elseif ( 'object' == $following->foreign_type )
                $following->entity = Object::find( $following->foreign_id );
            else
                $following->entity = User::find( $following->foreign_id );

            $entities[] = $following;
        }

        return $response->result( $entities );
    }

    public function follow( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        $exists = Follow::where( 'foreign_id', '=', $id )
                        ->where( 'foreign_type', '=', 'user' )
                        ->where( 'author', '=', auth()->user()->id )
                        ->first();

        if ( $exists )
            return $response->error( 'This user is already followed by the authenticated user' );

        $follow = [
            'foreign_id'    => $id,
            'foreign_type'  => 'user',
            'author'        => auth()->user()->id
        ];

        try {
            Follow::create( $follow );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        auth()->user()->count_following++;
        auth()->user()->save();

        $user->count_follows++;
        $user->save();

        return $response->success( 'Follow recorded successfully' );
    }

    public function follows( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        return $response->result( $user->follows() );
    }

    public function friend( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        if ( auth()->user()->id == $user->id )
            return $response->error( 'A user cannot befriend himself' );

        $inputs = [
            'from_id'       => auth()->user()->id,
            'from_accepted' => 1,
            'to_id'         => $user->id,
            'to_accepted'   => 0
        ];

        try {
            Friend::create( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'User successfully added as a friend' );
    }

    public function unfriend( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        if ( auth()->user()->id == $user->id )
            return $response->error( 'A user cannot unfriend himself' );

        $friendship = Friend::where( 'from_id', '=', auth()->user()->id )
                            ->where( 'to_id', '=', $user->id )
                            ->first();

        if ( is_null( $friendship ) )
            $friendship = Friend::where( 'to_id', '=', auth()->user()->id )
                                ->where( 'from_id', '=', $user->id )
                                ->first();

        if ( is_null( $friendship ) )
            return $response->error( 'Friendship does not exists, nothing to do here' );

        $friendship->delete();

        return $response->success( 'User successfully unfriended' );
    }

    public function friends( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        $friend_ids = [];

        $frs = \App\Friend::where( 'from_id', '=', $user->id )
                          ->orWhere( 'to_id', '=', $user->id )
                          ->get();

        if ( ! sizeof( $frs ) > 0 )
            return $response->result( $friend_ids );

        foreach ( $frs as $fr ) {
            if ( ! $fr->from_accepted )
                continue;

            if ( ! $fr->to_accepted )
                continue;

            if ( $fr->from_id == $user->id )
                $friend_ids[] = $fr->to_id;
            else
                $friend_ids[] = $fr->from_id;
        }

        $friends = User::whereIn( 'id', $friend_ids )->paginate( $this->pp );

        return $response->result( $friends->toArray() );
    }

    public function feedbacks( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        return $response->result( $user->feedbacks );
    }

    public function messages_sent( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        $messages = Message::where( 'sender', '=', $user->id )
                           ->paginate( $this->pp );

        return $response->result( $messages->toArray() );
    }
    
    public function messages_received( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        $messages = Message::where( 'recipient', '=', $user->id )
                           ->paginate( $this->pp );

        return $response->result( $messages->toArray() );
    }

    public function invites_sent( $id, ApiResponse $response ) {
        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        return $response->result( $user->invites );
    }

    public function getPreference( $key, ApiResponse $response ) {
        $preferences = config( 'user_preferences' );

        if ( ! in_array( $key, $preferences ) )
            return $response->error( 'Preference does not exists' );

        $user_pref = UserPreference::firstOrNew( ['user_id' => auth()->user()->id, 'key' => $key] );
        $user_pref->key = $key;

        return $response->result( $user_pref );
    }

    public function getAllPreferences( ApiResponse $response ) {
        $preferences = config( 'user_preferences' );

        $user_prefs = [];

        foreach ( $preferences as $key ) {
            $user_pref = UserPreference::where( 'key', '=', $key )
                                       ->where( 'user_id', '=', auth()->user()->id )
                                       ->first();

            if ( is_null( $user_pref ) )
                $user_prefs[$key] = 0;
            else
                $user_prefs[$key] = $user_pref->value;
        }

        return $response->result( $user_prefs );
    }

    public function setPreference( $key, Request $request, ApiResponse $response ) {
        $preferences = config( 'user_preferences' );

        if ( ! in_array( $key, $preferences ) )
            return $response->error( 'Preference does not exists' );

        $preference = UserPreference::firstOrNew( [
            'user_id'   => auth()->user()->id,
            'key'       => $key
        ] );

        $preference->key = $key;
        $preference->value = $request->get( 'value' );

        try {
            $preference->save();
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Preference successfully updated' );
    }

    public function setCommissionRate( $id, Request $request, ApiResponse $response ) {
        if ( auth()->user()->group_id > 2 )
            abort( 403 );

        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        $rate = doubleval( $request->get( 'rate' ) );

        $user->commission_rate = $rate;
        $user->commission_rate_flag = 1;

        $user->save();

        return $response->success( 'Commission rate was set successfully' );
    }

    public function setCommissionExchange( $id, Request $request, ApiResponse $response ) {
        if ( auth()->user()->group_id > 2 )
            abort( 403 );

        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        $exchange = doubleval( $request->get( 'exchange' ) );

        $user->commission_exchange = $exchange;

        $user->save();

        return $response->success( 'Commission exchange was set successfully' );
    }

    public function payCommission( $id, Request $request, ApiResponse $response ) {
        if ( auth()->user()->group_id > 2 )
            abort( 403 );

        $user = User::find( $id );

        if ( is_null( $user ) )
            abort( 404 );

        $amount = doubleval( $request->get( 'amount' ) );

        if ( $amount > $user->commissions )
            return $response->error( 'Amount is too big' );

        $user->commissions = $user->commissions - $amount;
        $user->save();

        $payed = doubleval( $user->commission_exchange ) * $amount;

        return $response->result( $payed );
    }

}
