<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guardian extends Model {
    protected $table = 'guardians';
    protected $primaryKey = 'guardian_id';
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'contact_no',
        'is_available'
    ];

    public function students() {
        return $this->hasMany('App\Student', 'student_id');
    }
}
