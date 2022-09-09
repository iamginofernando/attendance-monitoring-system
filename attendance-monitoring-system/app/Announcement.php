<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'announcements';

    protected $primaryKey = 'announcement_id';

    protected $fillable = [
        'content',
        'user_id',
    ];
}
