<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
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
                'name'      => 'required|max:255',
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

    public function postRegistration( Request $request, ApiResponse $response ) {
        $inputs = $request->all();

        $this->validator(
            $inputs
        );
        $user = $this->create( $inputs );

        if(!($user instanceof User))
            return $response->error( 'Register failed' );

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
