<?php

namespace App\Http\Controllers;

use App\Helper\Status;
use App\Student;
use Config;
use Illuminate\Http\Request;
use Response;
use Validator;

class StudentController extends Controller
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
        $students = Student::all();
        $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
        $this->status->message = '';

        return Response::json([
            'status' => $this->status,
            'students' => $students, ],
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
        $student = '';

        // Validate input
        $validator = Validator::make($request->all(), [
            'section' => 'required',
            'contact_no' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'required',
            'profile_img' => 'required',
            'birthday' => 'required|date_format:Y/m/d',
            'address' => 'required',
            'email' => 'required|email|unique:students',
        ]);

        // Return error
        if ($validator->fails()) {
            $this->status->code = Config::get('constants.STATUS_CODE_FAILED.CODE');
            $this->status->message = $validator->messages();
            $this->responseCode = Config::get('constants.INTERNAL');
        } else {

            // Create student
            $student = Student::create([
                'section' => $request['section'],
                'contact_no' => $request['contact_no'],
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'middle_name' => $request['middle_name'],
                'profile_img' => $request['profile_img'],
                'birthday' => $request['birthday'],
                'address' => $request['address'],
                'email' => $request['email'],
            ]);

            $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
            $this->status->message = Config::get('constants.STATUS_CODE_SUCCESS.MESSAGES.STUDENT_ADDED');
        }

        return Response::json([
            'status' => $this->status,
            'student' => $student, ],
            $this->responseCode
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        return Response::json([
            'student' => collect($student)->toArray(), ],
            $this->responseCode
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        return Response::json([
            'student' => collect($student)->toArray(), ],
            $this->responseCode
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {

        // Validate input
        $validator = Validator::make($request->all(), [
            'section' => 'required',
            'contact_no' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'required',
            'profile_img' => 'required',
            'birthday' => 'required',
            'address' => 'required',
            'email' => 'required|email',
        ]);

        // Return error
        if ($validator->fails()) {
            $this->status->code = Config::get('constants.STATUS_CODE_FAILED.CODE');
            $this->status->message = $validator->messages();
            $this->responseCode = Config::get('constants.INTERNAL');
        } else {

        // Update student
            $student->student_rfid = $request->student_rfid;
            $student->section = $request->section;
            $student->contact_no = $request->contact_no;
            $student->first_name = $request->first_name;
            $student->last_name = $request->last_name;
            $student->middle_name = $request->middle_name;
            $student->profile_img = $request->profile_img;
            $student->birthday = $request->birthday;
            $student->address = $request->address;
            $student->email = $request->email;
            $student->save();

            $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
            $this->status->message = Config::get('constants.STATUS_CODE_SUCCESS.MESSAGES.UPDATED_STUDENT');
        }

        return Response::json([
            'status' => $this->status,
            'student' => $student, ],
        $this->responseCode
    );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $this->status = new Status;
        $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
        $this->status->message = Config::get('constants.STATUS_CODE_SUCCESS.MESSAGES.DELETED_STUDENT');

        $student->delete();

        return Response::json([
            'status' => $this->status, ],
            $this->responseCode
        );
    }
}
