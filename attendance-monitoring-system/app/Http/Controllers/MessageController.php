<?php

namespace App\Http\Controllers;

use App\Helper\Status;
use App\Message;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;
use Validator;

class MessageController extends Controller
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
        $messages = Message::all();
        $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
        $this->status->message = '';

        return Response::json([
            'status' => $this->status,
            'messages' => $messages, ],
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
        $message = '';

        // Validate input
        $validator = Validator::make($request->all(), [
            'check_in' => 'required|min:30',
            'check_out' => 'required|min:30',
        ]
        );

        // Return error
        if ($validator->fails()) {
            $this->status->code = Config::get('constants.STATUS_CODE_FAILED.CODE');
            $this->status->message = $validator->messages();
            $this->responseCode = Config::get('constants.INTERNAL');
        } else {

            // Create message
            $message = Message::create([
                'check_in' => $request['check_in'],
                'check_out' => $request['check_out'],
                'user_id' => Auth::user()->user_id,
            ]);

            $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
            $this->status->message = Config::get('constants.STATUS_CODE_SUCCESS.MESSAGES.MESSAGE_ADDED');
        }

        return Response::json([
            'status' => $this->status,
            'message' => $message, ],
            $this->responseCode
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
        $this->status->message = '';

        return Response::json([
            'status' => $this->status,
            'message' => $message, ],
            $this->responseCode
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
        $this->status->message = '';

        return Response::json([
            'status' => $this->status,
            'message' => $message, ],
            $this->responseCode
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'check_in' => 'required|min:30',
            'check_out' => 'required|min:30',
        ]
    );

        // Return error
        if ($validator->fails()) {
            $this->status->code = Config::get('constants.STATUS_CODE_FAILED.CODE');
            $this->status->message = $validator->messages();
            $this->responseCode = Config::get('constants.INTERNAL');
        } else {

        // Update message
            $message->check_in = $request->check_in;
            $message->check_out = $request->check_out;
            $message->save();

            $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
            $this->status->message = Config::get('constants.STATUS_CODE_SUCCESS.MESSAGES.MESSAGE_UPDATED');
        }

        return Response::json([
            'status' => $this->status,
            'message' => $message, ],
        $this->responseCode
    );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        $this->status = new Status;
        $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
        $this->status->message = Config::get('constants.STATUS_CODE_SUCCESS.MESSAGES.DELETED_MESSAGE');

        $message->delete();

        return Response::json([
            'status' => $this->status, ],
            $this->responseCode
        );
    }
}
