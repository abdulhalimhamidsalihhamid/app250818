<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /** حماية بسيطة بدون ميدل وير */
    private function ensureAdmin(): void
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'غير مسموح — هذه الصفحة للمشرف فقط.');
        }
    }

    public function index(Request $request)
    {
        $this->ensureAdmin();

        $q = User::query()->latest('id');

        if ($s = $request->query('q')) {
            $q->where(function($w) use ($s) {
                $w->where('name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhere('national_id', 'like', "%$s%");
            });
        }

        $users = $q->paginate(15)->appends($request->query());
        return view('pages.admin.users', compact('users', 's'));
    }

    public function updateRole(Request $request, User $user)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'role' => ['required', Rule::in(['admin','teacher','student','staff'])],
        ]);

        if ($user->id === $request->user()->id && $data['role'] !== 'admin') {
            return back()->withErrors(['role' => 'لا يمكنك إزالة صلاحية المشرف عن نفسك.']);
        }

        $user->role = $data['role'];
        $user->save();

        return back()->with('success', 'تم تحديث الدور بنجاح ✅');
    }

    public function resetPassword(Request $request, User $user)
    {
        $this->ensureAdmin();

        $request->validate([
            'password' => ['required','string','min:8','confirmed'],
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'تمت إعادة تعيين كلمة المرور ✅');
    }

    public function destroy(Request $request, User $user)
    {
        $this->ensureAdmin();

        if ($user->id === $request->user()->id) {
            return back()->withErrors(['user' => 'لا يمكنك حذف نفسك.']);
        }

        if ($user->role === 'admin' && User::where('role','admin')->count() <= 1) {
            return back()->withErrors(['user' => 'لا يمكن حذف آخر مشرف في النظام.']);
        }

        $user->delete();
        return back()->with('success', 'تم حذف المستخدم 🗑️');
    }
}
