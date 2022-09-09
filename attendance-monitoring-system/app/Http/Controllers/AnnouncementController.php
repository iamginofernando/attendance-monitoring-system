<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\Helper\Status;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;
use Validator;

class AnnouncementController extends Controller
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
        $announcements = Announcement::all();
        $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
        $this->status->message = '';

        return Response::json([
            'status' => $this->status,
            'announcements' => $announcements, ],
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
        $announcement = '';

        // Validate input
        $validator = Validator::make($request->all(), [
            'content' => 'required|min:30',
        ]
        );

        // Return error
        if ($validator->fails()) {
            $this->status->code = Config::get('constants.STATUS_CODE_FAILED.CODE');
            $this->status->message = $validator->messages();
            $this->responseCode = Config::get('constants.INTERNAL');
        } else {

            // Create announcement
            $announcement = Announcement::create([
                'content' => $request['content'],
                'user_id' => Auth::user()->user_id,
            ]);

            $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
            $this->status->message = Config::get('constants.STATUS_CODE_SUCCESS.MESSAGES.ANNOUNCEMENT_ADDED');
        }

        return Response::json([
            'status' => $this->status,
            'announcement' => $announcement, ],
            $this->responseCode
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
        $this->status->message = '';

        return Response::json([
            'status' => $this->status,
            'announcement' => $announcement, ],
            $this->responseCode
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcement $announcement)
    {
        $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
        $this->status->message = '';

        return Response::json([
            'status' => $this->status,
            'announcement' => $announcement, ],
            $this->responseCode
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Announcement $announcement)
    {

        // Validate input
        $validator = Validator::make($request->all(), [
            'content' => 'required|min:30',
        ]
        );

        // Return error
        if ($validator->fails()) {
            $this->status->code = Config::get('constants.STATUS_CODE_FAILED.CODE');
            $this->status->message = $validator->messages();
            $this->responseCode = Config::get('constants.INTERNAL');
        } else {

            // Update announcement
            $announcement->content = $request->content;
            $announcement->save();

            $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
            $this->status->message = Config::get('constants.STATUS_CODE_SUCCESS.MESSAGES.ANNOUNCEMENT_UPDATED');
        }

        return Response::json([
            'status' => $this->status,
            'announcement' => $announcement, ],
            $this->responseCode
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        $this->status = new Status;
        $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
        $this->status->message = Config::get('constants.STATUS_CODE_SUCCESS.MESSAGES.DELETED_ANNOUNCEMENT');

        $announcement->delete();

        return Response::json([
            'status' => $this->status, ],
            $this->responseCode
        );
    }
}
