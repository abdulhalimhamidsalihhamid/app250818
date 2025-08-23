<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    // 1) التحقق مع رسالة خطأ إن لم نجد الرقم الوطني في قواعد المدرسة
    protected function validator(array $data)
    {
        $v = Validator::make($data, [
            'name'         => ['required','string','max:255'],
            'national_id'  => ['required','string','max:32','unique:users,national_id'],
            'email'        => ['required','string','email','max:255','unique:users,email'],
            'password'     => ['required','string','min:8','confirmed'],
        ], [
            'national_id.required' => 'الرقم الوطني مطلوب.',
            'national_id.unique'   => 'هذا الرقم الوطني مسجّل كمستخدم بالفعل.',
        ]);

        // فحص وجود الرقم الوطني في (students/teachers/staff)
        $v->after(function($validator) use ($data) {
            $nid = $data['national_id'] ?? null;
            if ($nid) {
                $exists = Student::where('national_id', $nid)->exists()
                       || Teacher::where('national_id', $nid)->exists()
                       || Staff::where('national_id', $nid)->exists();

                if (! $exists) {
                    $validator->errors()->add(
                        'national_id',
                        'لم يتم تسجيلك في النظام. يرجى مراجعة مسؤول المدرسة لإضافتك أولاً.'
                    );
                }
            }
        });

        return $v;
    }

    // 2) الإنشاء + تحديد الدور تلقائياً، مع فحص احتياطي
    protected function create(array $data)
    {
        // نحدّد الدور حسب وجود الرقم في الجداول
        $role = 'none';

        $student = Student::where('national_id', $data['national_id'])->first();
        if ($student) {
            $role = 'student';
        } else {
            $teacher = Teacher::where('national_id', $data['national_id'])->first();
            if ($teacher) {
                $role = 'teacher';
            } else {
                $staff = Staff::where('national_id', $data['national_id'])->first();
                if ($staff) $role = 'staff';
            }
        }

        // إن لم نجد أي سجل: نرجع بخطأ واضح (حماية إضافية)
        if ($role === 'none') {
            throw ValidationException::withMessages([
                'national_id' => 'لم يتم تسجيلك في النظام. يرجى مراجعة مسؤول المدرسة لإضافتك أولاً.',
            ]);
        }

        // إنشاء المستخدم
        $user = User::create([
            'name'        => $data['name'],
            'national_id' => $data['national_id'],
            'email'       => $data['email'],
            'password'    => Hash::make($data['password']),
            'role'        => $role, // admin/teacher/student/staff
        ]);

        // ربط user_id في السجل المطابق
        if (isset($student) && $student) {
            $student->update(['user_id' => $user->id]);
        } elseif (isset($teacher) && $teacher) {
            $teacher->update(['user_id' => $user->id]);
        } elseif (isset($staff) && $staff) {
            $staff->update(['user_id' => $user->id]);
        }

        return $user;
    }
}
