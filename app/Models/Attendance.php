<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'role','person_id','date','term','year','status','notes','created_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
