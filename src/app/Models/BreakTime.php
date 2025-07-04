<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakTime extends Model
{
    use HasFactory;
    protected $table = 'breaks';
    protected $fillable = [
        'user_id',
        'attendance_id',
        'start_break',
        'end_break',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function attendance(){
        return $this->belongsTo('App\Models\Attendance');
    }
}
