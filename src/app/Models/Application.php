<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'attendance_id',
        'status',
        'applied_at',
        'approved_by_user_id',
    ];

    public function attendance(){
        return $this->belongsTo(Attendance::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
