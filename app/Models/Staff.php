<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','national_id','email','phone',
        'job_title','department','hire_date',
        'salary','status','address',
        'avatar_path','created_by',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'salary'    => 'decimal:2',
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}
}
