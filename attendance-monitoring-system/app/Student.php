<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'student_id';
    protected $fillable = [
        'student_rfid',
        'section',
        'contact_no',
        'first_name',
        'last_name',
        'middle_name',
        'profile_img',
        'birthday',
        'address',
        'email'
    ];
}
