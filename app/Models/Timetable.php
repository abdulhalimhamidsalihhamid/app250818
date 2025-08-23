<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
protected $fillable = [
    'day','specialization','grade',
    'period1','period2','period3','period4','period5','period6','period7'
];
}
