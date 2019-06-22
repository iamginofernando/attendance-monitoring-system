<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'transaction_id';
    protected $fillable = [
        'student_id',
        'check_in',
        'check_out'
    ];
}
