<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','body',
        'published_at','expires_at',
        'audience',
        'media_type','media_path',
        'created_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at'   => 'date',
    ];
}
