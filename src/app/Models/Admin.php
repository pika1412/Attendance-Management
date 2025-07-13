<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Admin extends Authenticatable
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'password',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
