<?php

namespace App\Http\Controllers\Inputs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\Teacher;
use App\Models\Timetable;

class TeachersController extends Controller
{
    public function index()
    {
            $subjects = Timetable::query()
        ->get(['period1','period2','period3','period4','period5','period6','period7'])
        ->flatMap(function ($t) {
            return [
                $t->period1, $t->period2, $t->period3, $t->period4,
                $t->period5, $t->period6, $t->period7,
            ];
        })
        ->filter(fn($s) => $s && trim($s) !== '')
        ->map(fn($s) => trim($s))
        ->unique()
        ->sort()
        ->values();

        $teachers = Teacher::latest('id')->paginate(10);
        return view('pages.inputs.teachers', compact(['teachers','subjects']));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => ['required','string','max:255'],
            'national_id'  => ['required','string','max:32','unique:teachers,national_id'],
            'email'        => ['nullable','email','max:255'],
            'phone'        => ['nullable','string','max:50'],

            'subject'      => ['nullable','string','max:120'],
            'qualification'=> ['nullable','string','max:120'],
            'department'   => ['nullable','string','max:120'],
            'grade_levels' => ['nullable','string','max:120'],

            'hire_date'    => ['nullable','date'],
            'salary'       => ['nullable','numeric','min:0'],
            'status'       => ['required', Rule::in(['نشط','موقوف','منتهٍ'])],

            'address'      => ['nullable','string'],
            'avatar'       => ['nullable','file','mimes:jpg,jpeg,png,webp','max:5120'],
        ], [
            'name.required'        => 'اسم المعلم مطلوب.',
            'national_id.required' => 'الرقم الوطني مطلوب.',
            'national_id.unique'   => 'هذا الرقم الوطني مسجل مسبقًا.',
            'status.in'            => 'حالة غير صالحة.',
        ]);

        $path = null;
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('teachers/avatars', 'public');
        }

        Teacher::create([
            'name'         => $data['name'],
            'national_id'  => $data['national_id'],
            'email'        => $data['email'] ?? null,
            'phone'        => $data['phone'] ?? null,
            'subject'      => $data['subject'] ?? null,
            'qualification'=> $data['qualification'] ?? null,
            'department'   => $data['department'] ?? null,
            'grade_levels' => $data['grade_levels'] ?? null,
            'hire_date'    => $data['hire_date'] ?? null,
            'salary'       => $data['salary'] ?? null,
            'status'       => $data['status'],
            'address'      => $data['address'] ?? null,
            'avatar_path'  => $path,
            'created_by'   => auth()->id(),
        ]);

        return back()->with('success', 'تم إضافة المعلم بنجاح ✅');
    }

    public function edit(Teacher $teacher)
    {
        return view('pages.inputs.teachers_edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'name'         => ['required','string','max:255'],
            'national_id'  => ['required','string','max:32','unique:teachers,national_id,'.$teacher->id],
            'email'        => ['nullable','email','max:255'],
            'phone'        => ['nullable','string','max:50'],

            'subject'      => ['nullable','string','max:120'],
            'qualification'=> ['nullable','string','max:120'],
            'department'   => ['nullable','string','max:120'],
            'grade_levels' => ['nullable','string','max:120'],

            'hire_date'    => ['nullable','date'],
            'salary'       => ['nullable','numeric','min:0'],
            'status'       => ['required', Rule::in(['نشط','موقوف','منتهٍ'])],

            'address'      => ['nullable','string'],
            'avatar'       => ['nullable','file','mimes:jpg,jpeg,png,webp','max:5120'],
        ]);

        if ($request->hasFile('avatar')) {
            if ($teacher->avatar_path) {
                Storage::disk('public')->delete($teacher->avatar_path);
            }
            $teacher->avatar_path = $request->file('avatar')->store('teachers/avatars', 'public');
        }

        $teacher->fill([
            'name'         => $data['name'],
            'national_id'  => $data['national_id'],
            'email'        => $data['email'] ?? null,
            'phone'        => $data['phone'] ?? null,
            'subject'      => $data['subject'] ?? null,
            'qualification'=> $data['qualification'] ?? null,
            'department'   => $data['department'] ?? null,
            'grade_levels' => $data['grade_levels'] ?? null,
            'hire_date'    => $data['hire_date'] ?? null,
            'salary'       => $data['salary'] ?? null,
            'status'       => $data['status'],
            'address'      => $data['address'] ?? null,
        ])->save();

        return redirect()->route('inputs.teachers')->with('success', 'تم تحديث بيانات المعلم ✅');
    }

    public function destroy(Teacher $teacher)
    {
        if ($teacher->avatar_path) {
            Storage::disk('public')->delete($teacher->avatar_path);
        }
        $teacher->delete();
        return back()->with('success', 'تم حذف المعلم 🗑️');
    }
}
