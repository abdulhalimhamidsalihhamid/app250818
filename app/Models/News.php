<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    // الحقول المسموح بتمريرها عبر create()/update()
    protected $fillable = [
        'title',
        'published_at',
        'excerpt',
        'body',
        'category',
        'media_type',   // image | video | null
        'media_path',   // مسار الملف داخل storage
        'created_by',
    ];

    // تحويلات
    protected $casts = [
        'published_at' => 'datetime',
    ];
}
