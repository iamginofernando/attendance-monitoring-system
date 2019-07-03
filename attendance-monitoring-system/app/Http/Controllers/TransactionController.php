<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Student;
use App\Helper\Status;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use Response;
use Validator;
use Config;


class TransactionController extends Controller
{

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
        $transactions = Transaction::all();
        $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
        $this->status->message = '';

        return Response::json(array(
            'status' => $this->status,
            'transactions' => $transactions),
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
        $validator = Validator::make($request->all(),[
            'student_rfid' => 'required|exists:students,student_rfid'
        ]);

        // Return error
        if ($validator->fails()) {
            $this->status->code = Config::get('constants.STATUS_CODE_FAILED.CODE');
            $this->status->message = $validator->messages();
            $this->responseCode = Config::get('constants.INTERNAL');
        } else {

            $student_id = Student::where('student_rfid', $request->student_rfid)->pluck('student_id')->first();
            $transaction = Transaction::where('student_id', $student_id)->latest()->first();
            if($transaction) {
           
                if(Carbon::now()->diffInSeconds($transaction->check_in) > 5 && $transaction->check_out == null) {
                   
                    // Add check out on student
                    $transaction->check_out = Carbon::now();
                    $transaction->save();
                } else if (Carbon::now()->diffInSeconds($transaction->check_in) > 5 && $transaction->check_out) {

                    // Create transaction
                    $transaction = Transaction::create([
                        'student_id' => $student_id,
                        'check_in' => Carbon::now()
                    ]);
                }
            } else {

                 // Create transaction
                 $transaction = Transaction::create([
                    'student_id' => $student_id,
                    'check_in' => Carbon::now()
                ]);
            }
            
            $this->status->code = Config::get('constants.STATUS_CODE_SUCCESS.CODE');
            $this->status->message = Config::get('constants.STATUS_CODE_SUCCESS.MESSAGES.TRANSACTION_ADDED');
        }

        return Response::json(array(
            'status' => $this->status,
            'transaction' => $transaction),
            $this->responseCode
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        $transaction->student;

        return Response::json(array(
            'transaction' => collect($transaction)->toArray()),
            $this->responseCode
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
