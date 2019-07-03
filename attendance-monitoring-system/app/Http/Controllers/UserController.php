<?php

namespace App\Http\Controllers;

use App\User;
use App\Helper\Status;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Response;
use Validator;
use Config;


class UserController extends Controller
{

    private $status;
    private $responseCode;

    /**
     * Initialize variables
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        //block init
        $this->status = new Status; 
        $this->responseCode = Config::get('constants.OK'); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
        $this->status->message = '';

        return Response::json(array(
            'status' => $this->status,
            'users' => $users),
            $this->responseCode
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = '';     

        // Validate input
        $validator = Validator::make($request->all(),[
                'name' => 'required',
                'password' => 'required|min:8',
                'email' => 'required|email|unique:users'
            ]
        );

        // Return error
        if ($validator->fails()) {
            $this->status->code = Config::get('constants.STATUS_CODE_FAILED.CODE');
            $this->status->message = $validator->messages();
            $this->responseCode = Config::get('constants.INTERNAL');
        } else {

            // Create user
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'api_token' => Str::random(60),
            ]);

            $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
            $this->status->message = Config::get('constants.STATUS_CODE_SUCCESS.MESSAGES.USER_REGISTERED');
        }

        return Response::json(array(
            'status' => $this->status,
            'user' => $user),
            $this->responseCode
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return Response::json(array(
            'user' => collect($user)->toArray()),
            $this->responseCode
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return Response::json(array(
            'user' => collect($user)->toArray()),
            $this->responseCode
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {   

        // Validate input
        $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users'
            ]
        );

        // Return error
        if ($validator->fails()) {
            $this->status->code = Config::get('constants.STATUS_CODE_FAILED.CODE');
            $this->status->message = $validator->messages();
            $this->responseCode = Config::get('constants.INTERNAL');
        } else {

            // Update user
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
            $this->status->message = Config::get('constants.STATUS_CODE_SUCCESS.MESSAGES.UPDATED_USER');
        }

        return Response::json(array(
            'status' => $this->status,
            'user' => $user),
            $this->responseCode
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->status = new Status;
        $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
        $this->status->message = Config::get('constants.STATUS_CODE_SUCCESS.MESSAGES.DELETED_USER');

        $user->delete();

        return Response::json(array(
            'status' => $this->status),
            $this->responseCode
        );
    }

    /**
     * Login user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        $user = '';     

        // Validate input
        $validator = Validator::make($request->all(),[
                'email' => 'required',
                'password' => 'required'
            ]
        );

        // Return error
        if ($validator->fails()) {
            $this->status->code = Config::get('constants.STATUS_CODE_FAILED.CODE');
            $this->status->message = $validator->messages();
            $this->responseCode = Config::get('constants.INTERNAL');
        } else {
            
            // Login user and return user's credentials
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials, $request->has('remember'))) {
                $user = Auth::user();
                $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
                $this->status->message = Config::get('constants.STATUS_CODE_SUCCESS.SUCCESS_LOGIN');
            } else {
                $this->status->code = Config::get('constants.STATUS_CODE_FAILED.CODE');
                $this->status->message = Config::get('constants.STATUS_CODE_FAILED.MESSAGES.FAILED_LOGIN');
            }
        }

        return Response::json(array(
            'status' => $this->status,
            'user' => $user),
            $this->responseCode
        );
    }

    /**
     * Login user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {

        Auth::logout();
        $this->status = new Status;
        $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
        $this->status->message = Config::get('constants.STATUS_CODE_SUCCESS.SUCCESS_LOGOUT');

        return Response::json(array(
            'status' => $this->status),
            $this->responseCode
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $user = '';     

        // Validate input
        $validator = Validator::make($request->all(),[
                'name' => 'required',
                'password' => 'required|min:8',
                'email' => 'required|email|unique:users'
            ]
        );

        // Return error
        if ($validator->fails()) {
            $this->status->code = Config::get('constants.STATUS_CODE_FAILED.CODE');
            $this->status->message = $validator->messages();
            $this->responseCode = Config::get('constants.INTERNAL');
        } else {

            // Create user
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'api_token' => Str::random(60),
            ]);

            $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
            $this->status->message = Config::get('constants.STATUS_CODE_SUCCESS.USER_REGISTERED');
        }

        return Response::json(array(
            'status' => $this->status,
            'user' => $user),
            $this->responseCode
        );
    }
 
}
