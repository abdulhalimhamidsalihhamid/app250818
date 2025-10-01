<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
     protected $fillable = [
        'code','student_name','class_name','department','round_name','seat_no',
        'academic_year','grade_of_year','general_remark','total_marks','percentage','issue_date'
    ];
}
