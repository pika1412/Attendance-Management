<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\BreakTime;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'work_date',
        'status',
        'start_time',
        'end_time',
        'memo',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function breakTimes()
{
    return $this->hasMany('App\Models\BreakTime');
}

public function application()
{
    return $this->hasOne(Application::class);
}

    public function getTotalTimeFormattedAttribute(){
        if(!$this->start_time || !$this->end_time) return null;

        $workSeconds = Carbon::parse($this->start_time)->diffInSeconds(Carbon::parse($this->end_time));

        $breakSeconds = $this->break_time ? $this->break_time * 60 : 0;

        $netSeconds = max($workSeconds - $breakSeconds, 0);

        $h = floor($netSeconds / 3600);
        $m = floor($netSeconds % 3600 /60);

        return sprintf('%02d:%02d',$h,$m);
    }

    public function getFormattedBreakTimeAttribute(){
        $latestBreak = $this->breakTimes()->latest()->first();

        if(!$latestBreak || !$latestBreak->start_break || !$latestBreak->end_break){
            return null;
        }

        $breakSeconds = Carbon::parse($latestBreak->start_break)->diffInSeconds(Carbon::parse($latestBreak->end_break));

        $h = floor($breakSeconds / 3600);
        $m = floor(($breakSeconds % 3600) / 60);

        return sprintf('%02d:%02d',$h,$m);
    }
}
