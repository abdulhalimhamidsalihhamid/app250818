<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'student_name','student_number','email','dob','national_id','phone','gender',
        'department','class_name','enrollment_date','blood_type','address',
        'guardian_name','guardian_phone','created_by'
    ];

    protected $casts = [
        'dob' => 'date',
        'enrollment_date' => 'date',
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}
}
