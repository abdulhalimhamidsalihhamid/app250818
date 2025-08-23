<?php

namespace App\Http\Controllers\Inputs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\Staff;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::latest('id')->paginate(10);
        return view('pages.inputs.staff', compact('staff'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required','string','max:255'],
            'national_id' => ['required','string','max:32','unique:staff,national_id'],
            'email'       => ['nullable','email','max:255'],
            'phone'       => ['nullable','string','max:50'],

            'job_title'   => ['nullable','string','max:120'],
            'department'  => ['nullable','string','max:120'],
            'hire_date'   => ['nullable','date'],

            'salary'      => ['nullable','numeric','min:0'],
            'status'      => ['required', Rule::in(['نشط','موقوف','منتهٍ'])],

            'address'     => ['nullable','string'],
            'avatar'      => ['nullable','file','mimes:jpg,jpeg,png,webp','max:5120'],
        ], [
            'name.required'        => 'اسم الموظف مطلوب.',
            'national_id.required' => 'الرقم الوطني مطلوب.',
            'national_id.unique'   => 'هذا الرقم الوطني مسجل مسبقًا.',
            'status.in'            => 'حالة غير صالحة.',
        ]);

        $path = null;
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('staff/avatars', 'public');
        }

        Staff::create([
            'name'        => $data['name'],
            'national_id' => $data['national_id'],
            'email'       => $data['email'] ?? null,
            'phone'       => $data['phone'] ?? null,
            'job_title'   => $data['job_title'] ?? null,
            'department'  => $data['department'] ?? null,
            'hire_date'   => $data['hire_date'] ?? null,
            'salary'      => $data['salary'] ?? null,
            'status'      => $data['status'],
            'address'     => $data['address'] ?? null,
            'avatar_path' => $path,
            'created_by'  => auth()->id(),
        ]);

        return back()->with('success', 'تم إضافة الموظف بنجاح ✅');
    }

    public function edit(Staff $staff)
    {
        return view('pages.inputs.staff_edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff)
    {
        $data = $request->validate([
            'name'        => ['required','string','max:255'],
            'national_id' => ['required','string','max:32','unique:staff,national_id,'.$staff->id],
            'email'       => ['nullable','email','max:255'],
            'phone'       => ['nullable','string','max:50'],

            'job_title'   => ['nullable','string','max:120'],
            'department'  => ['nullable','string','max:120'],
            'hire_date'   => ['nullable','date'],

            'salary'      => ['nullable','numeric','min:0'],
            'status'      => ['required', Rule::in(['نشط','موقوف','منتهٍ'])],

            'address'     => ['nullable','string'],
            'avatar'      => ['nullable','file','mimes:jpg,jpeg,png,webp','max:5120'],
        ]);

        // استبدال الصورة (اختياري)
        if ($request->hasFile('avatar')) {
            if ($staff->avatar_path) {
                Storage::disk('public')->delete($staff->avatar_path);
            }
            $staff->avatar_path = $request->file('avatar')->store('staff/avatars', 'public');
        }

        $staff->fill([
            'name'        => $data['name'],
            'national_id' => $data['national_id'],
            'email'       => $data['email'] ?? null,
            'phone'       => $data['phone'] ?? null,
            'job_title'   => $data['job_title'] ?? null,
            'department'  => $data['department'] ?? null,
            'hire_date'   => $data['hire_date'] ?? null,
            'salary'      => $data['salary'] ?? null,
            'status'      => $data['status'],
            'address'     => $data['address'] ?? null,
        ])->save();

        return redirect()->route('inputs.staff')->with('success', 'تم تحديث بيانات الموظف ✅');
    }

    public function destroy(Staff $staff)
    {
        if ($staff->avatar_path) {
            Storage::disk('public')->delete($staff->avatar_path);
        }
        $staff->delete();
        return back()->with('success', 'تم حذف الموظف 🗑️');
    }
}
