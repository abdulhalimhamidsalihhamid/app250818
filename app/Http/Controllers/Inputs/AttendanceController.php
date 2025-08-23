<?php

namespace App\Http\Controllers\Inputs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Staff;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        // فلاتر بسيطة للعرض
        $role      = $request->input('role');         // student/teacher/staff
        $person_id = $request->input('person_id');    // ID من جدول الدور
        $from      = $request->input('from');         // تاريخ من
        $to        = $request->input('to');           // تاريخ إلى

        // للحقول الافتراضية عند الإدخال
        $term = $request->input('term', 'الفصل الأول');
        $year = (int) $request->input('year', now()->year);
        $date = $request->input('date', now()->format('Y-m-d'));

        // قوائم الاختيار
        $students = Student::orderBy('student_name')->get(['id','student_name']);
        $teachers = class_exists(Teacher::class)
            ? Teacher::orderBy('name')->get(['id','name'])
            : collect();
        $staff    = class_exists(Staff::class)
            ? Staff::orderBy('name')->get(['id','name'])
            : collect();

        // عرض السجلات
        $q = Attendance::query()->latest('date')->latest('id');
        if ($role)      $q->where('role', $role);
        if ($person_id) $q->where('person_id', $person_id);
        if ($from && $to) $q->whereBetween('date', [$from, $to]);

        $records = $q->paginate(15);

        return view('pages.inputs.attendance', compact(
            'students','teachers','staff',
            'records','role','person_id','term','year','date','from','to'
        ));
    }

    public function store(Request $request)
    {
        $role = $request->input('role');

        // تحقق مشروط لوجود الشخص في الجدول الصحيح
        $personRule = ['required','integer'];
        if ($role === 'student') {
            $personRule[] = Rule::exists('students','id');
        } elseif ($role === 'teacher') {
            $personRule[] = Rule::exists('teachers','id');
        } elseif ($role === 'staff') {
            $personRule[] = Rule::exists('staff','id');
        }

        $data = $request->validate([
            'role'      => ['required', Rule::in(['student','teacher','staff'])],
            'person_id' => $personRule,
            'date'      => ['required','date'],
            'term'      => ['nullable','string','max:20'], // الفصل الأول/الثاني
            'year'      => ['nullable','integer','min:2000','max:2100'],
            'status'    => ['required', Rule::in(['حاضر','غائب','متأخر','مأذون'])],
            'notes'     => ['nullable','string'],
        ], [
            'role.required'      => 'حدد نوع الحضور (طالب/معلم/موظف).',
            'person_id.required' => 'اختر الشخص.',
            'date.required'      => 'حدد تاريخ الحضور.',
            'status.required'    => 'اختر حالة الحضور.',
        ]);

        // حفظ/تعديل بدون تكرار لنفس اليوم
        Attendance::updateOrCreate(
            [
                'role'      => $data['role'],
                'person_id' => $data['person_id'],
                'date'      => $data['date'],
            ],
            [
                'term'      => $data['term'] ?? null,
                'year'      => $data['year'] ?? null,
                'status'    => $data['status'],
                'notes'     => $data['notes'] ?? null,
                'created_by'=> auth()->id(),
            ]
        );

        return back()->with('success','تم الحفظ / التعديل بنجاح ✅');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return back()->with('success','تم حذف السجل 🗑️');
    }
}
