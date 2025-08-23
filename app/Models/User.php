<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name','email','password','national_id','role'];

    protected $hidden = ['password','remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // علاقات واحد-لواحد بناءً على user_id
    public function student(): HasOne   { return $this->hasOne(Student::class); }
    public function teacher(): HasOne   { return $this->hasOne(Teacher::class); }
    public function staff(): HasOne     { return $this->hasOne(Staff::class);   }
}
