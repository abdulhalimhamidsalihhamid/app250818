<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // غيّر القيم كما تريد
        $email = env('ADMIN_EMAIL', 'admin@school.test');
        $pass  = env('ADMIN_PASSWORD', '123456789');

        User::updateOrCreate(
            ['email' => $email],
            [
                'name'        => env('ADMIN_NAME', 'Super Admin'),
                'email'       => $email,
                'password'    => Hash::make($pass),
                'national_id' => null,   // اتركها null لو الأدمن ليس طالبًا
                'is_admin'    => true,
            ]
        );
    }
}
