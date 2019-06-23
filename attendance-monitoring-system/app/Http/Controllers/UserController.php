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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $status = new Status;
        $user = '';     

        // Validate input
        $validator = Validator::make($request->all(),[
                'name' => 'required|min:32',
                'password' => 'required|min:8',
                'email' => 'required|email|unique:users'
            ]
        );

        // Return error
        if ($validator->fails()) {
            $status->code = Config::get('constants.STATUS_CODE_FAILED.CODE');
            $status->message = $validator->messages();
        } else {

            // Create user
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'api_token' => Str::random(60),
            ]);

            $status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
            $status->message = Config::get('constants.STATUS_SUCCESS.USER_REGISTERED');
        }

        return Response::json(array(
            'status' => $status,
            'user' => $user),
            200
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
            200
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
            200
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
        $status = new Status;     

        // Validate input
        $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users'
            ]
        );

        // Return error
        if ($validator->fails()) {
            $status->code = Config::get('constants.STATUS_CODE_FAILED.CODE');
            $status->message = $validator->messages();
        } else {

            // Update user
            $user->name =  $request->name;
            $user->email =  $request->email;
            $user->save();

            $status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
            $status->message = Config::get('constants.STATUS_CODE_SUCCESS.UPDATED_USER');
        }

        return Response::json(array(
            'status' => $status,
            'user' => $user),
            200
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
        $status = new Status;
        $status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
        $status->message = Config::get('constants.STATUS_CODE_SUCCESS.DELETED_USER');

        $user->delete();

        return Response::json(array(
            'status' => $status),
            200
        );
    }

    /**
     * Login user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {

        $auth = false;
        $status = new Status;
        $user = '';     

        // Validate input
        $validator = Validator::make($request->all(),[
                'email' => 'required',
                'password' => 'required'
            ]
        );

        // Return error
        if ($validator->fails()) {
            $status->code = Config::get('constants.STATUS_CODE_FAILED.CODE');
            $status->message = $validator->messages();
        } else {
            // Login user and return user's credentials
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials, $request->has('remember'))) {
                $user = Auth::user();
                $status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
                $status->message = Config::get('constants.STATUS_CODE_SUCCESS.SUCCESS_LOGIN');
            } else {
                $status->code = Config::get('constants.STATUS_CODE_FAILED.CODE');
                $status->message = Config::get('constants.STATUS_CODE_FAILED.MESSAGES.FAILED_LOGIN');
            }
        }

        return Response::json(array(
            'status' => $status,
            'user' => $user),
            200
        );
    }

    /**
     * Login user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {

        $status = new Status;

        Auth::logout();
        $status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
        $status->message = Config::get('constants.STATUS_CODE_SUCCESS.SUCCESS_LOGOUT');

        return Response::json(array(
            'status' => $status),
            200
        );
    }
 
}
