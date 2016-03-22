<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Invite;
use App\Collection;
use App\PasswordResets;
use Validator;
use Mail;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Extensions\APIResponse;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware( 'guest', ['except' => 'getLogout'] );
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator( array $data ) {
        return Validator::make(
            $data,
            [
                'name'      => 'required|max:255|unique:users',
                'email'     => 'required|email|max:255|unique:users',
                'password'  => 'required|confirmed|min:6',
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create( array $data ) {
        return User::create(
            [
                'name'      => $data['name'],
                'email'     => $data['email'],
                'tags'      => $data['tags'],
                'password'  => bcrypt( $data['password'] ),
            ]
        );
    }

    public function getLogin( ApiResponse $response ) {
        if ( ! auth()->check() )
            return $response->error( 'User is not authenticated' );

        return $response->success( 'User is authenticated' );
    }
    public function postRestore( Request $request, ApiResponse $response ) {

        $inputs = $request->all();
        $check = Validator::make(
            $inputs,
            ['email'     => 'required|email|max:255|exists:users']
        );
        if( $check->fails() )
            return $response->error( 'Something wrong' );

        $user = User::where( 'email', '=', $inputs['email'] )->first();



        try {

            $token = uniqid();
            DB::table('password_resets')->insert(["email"=>$inputs['email'],'token'=> $token,'created_at'=>date('Y-m-d H:i:s')] );

        } catch ( Exception $e ) {

            return $response->error( $e->getMessage() );

        }

        Mail::send( 'emails.restore', ['user' => $user,'token'=> $token], function ( $mail ) use ( $inputs ) {
            $mail->to( $inputs['email'], '' )->subject( 'Restore password' );
            $mail->sender('info@gmail.com');
            $mail->from('info2@gmail.com');
        } );

        return $response->success( 'Email send to user!' );
    }

    public function getRestoreConfirm($token, Request $request, ApiResponse $response){


        $user_request = PasswordResets::where( 'token', '=', $token )->first();
        if(!$user_request)
            return $response->error( 'Something wrong' );

        $new_pass = uniqid();


        $user = User::where( 'email', '=',  $user_request->email )->first();
        $user->update(array('password' => bcrypt($new_pass)));
        Mail::send( 'emails.newPass', ['user' => $user,'password'=> $new_pass], function ( $mail ) use($user){
            $mail->to( $user->email , '' )->subject( 'New password' );
            $mail->sender('info@gmail.com');
            $mail->from('info@gmail.com');
        } );

        return \Redirect::to('user/login');


    }


    public function postRegistration( Request $request, ApiResponse $response ) {
        $inputs = $request->all();

        $this->validator(
            $inputs
        );
        $user = $this->create( $inputs );

        if(!($user instanceof User))
            return $response->error( 'Register failed' );

        // create collections
        if ( $user && $user->id ) {
            $collections = [
                'PromotedUsers',
                'Search',
                'Personalized',
                'Drafts',
                'Recent',
                'Trending',
                'Collections',
                'Great Riders',
                'Bike Night',
                'Menu',
                'Menu',
                'Menu',
                'Menu',
                'Menu',
                'Menu'
            ];

            for ( $i = 1; $i <= sizeof( $collections ); $i++ ) {
                $uc = new Collection();

                $uc->collection_id = $i;
                $uc->foreign_id = 0;
                $uc->foreign_type = 'object';
                $uc->name = $collections[$i-1];
                $uc->author = $user->id;

                $uc->save();
            }
        }

        // invitation accepted
        if ( $user && $user->email ) {
            $invites = Invite::where( 'email', '=', $user->email )
                             ->get();

            if ( sizeof( $invites ) > 0 )
                foreach ( $invites as $invite ) {
                    $invite->accepted = 1;
                    $invite->accepted_on = date_format( new DateTime( 'now' ), 'Y-m-d H:i:s' );

                    $invite->save();
                }
        }

        return $response->success( 'Register successfull' );
    }

    public function postLogin( Request $request, ApiResponse $response ) {
        $this->validate(
            $request,
            [
                $this->loginUsername()  => 'required',
                'password'              => 'required',
            ]
        );

        $throttles = $this->isUsingThrottlesLoginsTrait();

       /* if ( $throttles && $this->hasTooManyLoginAttempts( $request ) )
            return $this->sendLockoutResponse( $request );*/

        $credentials = $this->getCredentials( $request );

        if ( Auth::attempt( $credentials, $request->has( 'remember' ) ) )
            return $this->handleUserWasAuthenticated( $request, $throttles );

        if ( $throttles )
            $this->incrementLoginAttempts( $request );

        return $response->error( 'Login failed' );
    }

    protected function handleUserWasAuthenticated( Request $request, $throttles ) {
        $response = new APIResponse();

        if ( $throttles)
            $this->clearLoginAttempts( $request );

        if ( method_exists( $this, 'authenticated' ) )
            return $this->authenticated( $request, Auth::user() );

        return $response->success( 'Login successfull' );
    }

}
