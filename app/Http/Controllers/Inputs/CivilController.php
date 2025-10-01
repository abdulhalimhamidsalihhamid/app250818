<?php

namespace App\Http\Controllers\Inputs;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CivilController extends Controller
{
    // عرض صفحة الإدخال + قائمة الطلاب أسفلها
    public function create()
    {
        // تقدر ترتّب حسب اليوم/التخصص لاحقًا؛ الآن الأحدث أولًا
        $students = Student::latest()->get();

        return view('pages.inputs.civil', compact('students'));
    }

    // حفظ طالب جديد
    public function store(Request $request)
    {
        $data = $request->validate([
            'student_name'    => ['required','string','max:150'],
            'student_number'  => ['required','string','max:150'],
            'email'           => ['nullable','email','max:150'],
            'dob'             => ['nullable','date'],
            'national_id'     => ['required','string','max:32','unique:students,national_id'],
            'phone'           => ['nullable','string','max:32'],
            'gender'          => ['nullable', Rule::in(['male','female'])],
            'department'      => ['nullable','string','max:60'],
            'class_name'      => ['nullable','string','max:60'],
            'enrollment_date' => ['nullable','date'],
            'blood_type'      => ['nullable','string','max:8'],
            'address'         => ['nullable','string'],
            'guardian_name'   => ['nullable','string','max:150'],
            'guardian_phone'  => ['nullable','string','max:32'],
        ]);

        $data['created_by'] = auth()->id();
        Student::create($data);

        return redirect()->route('students.create')->with('status', 'تم حفظ بيانات الطالب بنجاح.');
    }

    // صفحة تعديل
    public function edit(Student $student)
    {
        
        return view('pages.inputs.civil_edit', compact('student'));
    }

    // تحديث
    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'student_name'     => ['required','string','max:150'],
            'student_number'   => ['required','string','max:150'],
            'email'            => ['nullable','email','max:150'],
            'dob'              => ['nullable','date'],
            'national_id'      => ['required','string','max:32', Rule::unique('students','national_id')->ignore($student->id)],
            'phone'            => ['nullable','string','max:32'],
            'gender'           => ['nullable', Rule::in(['male','female'])],
            'department'       => ['nullable','string','max:60'],
            'class_name'       => ['nullable','string','max:60'],
            'enrollment_date'  => ['nullable','date'],
            'blood_type'       => ['nullable','string','max:8'],
            'address'          => ['nullable','string'],
            'guardian_name'    => ['nullable','string','max:150'],
            'guardian_phone'   => ['nullable','string','max:32'],
        ]);

        $student->update($data);

        return redirect()->route('students.create')->with('status', 'تم تحديث بيانات الطالب.');
    }

    // حذف
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.create')->with('status', 'تم حذف الطالب.');
    }
}

