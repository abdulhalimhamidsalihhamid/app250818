<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // صفحة تعديل الحساب
    public function edit(Request $request)
    {
        $user = $request->user();
        return view('pages.profile.edit', compact('user'));
    }

    // حفظ التعديلات
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'        => ['required','string','max:255'],
            'national_id' => ['nullable','string','max:32', Rule::unique('users','national_id')->ignore($user->id)],
            'email'       => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'password'    => ['nullable','string','min:8','confirmed'], // اختياري
        ]);

        $user->name        = $data['name'];
        $user->national_id = $data['national_id'] ?? $user->national_id;
        $user->email       = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return back()->with('success', 'تم تحديث الحساب بنجاح ✅');
    }

    // حذف الحساب نهائياً
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required','current_password'], // يتأكد من كلمة المرور الحالية
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'تم حذف الحساب.');
    }
}
