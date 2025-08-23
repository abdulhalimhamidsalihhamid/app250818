<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;                  // ← مهم
use Illuminate\Support\Facades\Auth;         // ← مهم
use Illuminate\Support\Facades\Hash;         // ← مهم
use App\Models\User;                         // ← مهم

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    // نخلي اسم الحقل في الفورم هو "login"
    public function username()
    {
        return 'login';
    }

    // التحقق من المدخلات
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'login'    => ['required','string'],   // بريد أو رقم وطني
            'password' => ['required','string'],
        ]);
    }

    // نحسم نوع الحقل ونجهّز القيمة (مع lowercase للبريد)
    protected function resolveLoginFieldAndValue(Request $request): array
    {
        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'national_id';
        $value = $field === 'email' ? mb_strtolower($login) : $login;

        return [$field, $value];
    }

    // نعيد تعريف محاولة الدخول لنوحّد السلوك ونكشف سبب الفشل بوضوح
    protected function attemptLogin(Request $request)
    {
        [$field, $value] = $this->resolveLoginFieldAndValue($request);
        $password        = $request->input('password');
        $remember        = $request->boolean('remember');

        // ابحث عن المستخدم حسب Email أو National ID
        $user = User::where($field, $value)->first();

        if (!$user) {
            // لا يوجد مستخدم بهذه القيمة
            return false;
        }

        // تحقق كلمة المرور (لازم تكون مُشفّرة في DB)
        if (! Hash::check($password, $user->password)) {
            return false;
        }

        // سجّل الدخول يدويًا
        Auth::guard()->login($user, $remember);
        return true;
    }

    // (اختياري) لو أردت تستخدم attempt الافتراضي، أبقِ هذه الدالة بدلًا مما سبق:
    // protected function credentials(Request $request)
    // {
    //     [$field, $value] = $this->resolveLoginFieldAndValue($request);
    //     return [$field => $value, 'password' => $request->input('password')];
    // }
}
