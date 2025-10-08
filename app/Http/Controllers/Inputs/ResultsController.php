<?php

namespace App\Http\Controllers\Inputs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema;
use App\Models\Student;
use App\Models\Timetable;
use App\Models\StudentResult;

class ResultsController extends Controller
{

public function index(Request $request)
    {
        // نجلب فقط الأعمدة الموجودة (بدون grade)
        $students = Student::orderBy('student_name')
            ->get(['id','student_name','department']);

        // فلاتر
        $term = $request->input('term');
        $year = (int) $request->input('year', now()->year);

        $selectedStudentId = $request->input('student_id');
       // مخرجات للعرض
        $specialization = null;   // علمي/أدبي من الطلاب
        $subjects       = collect();
        $existing       = collect();
        $grade          = null;   // للحفاظ على التوافق مع الـ Blade، لكن غير مستخدم هنا

        if ($selectedStudentId) {
            $student        = Student::select(['id','student_name','department'])->findOrFail($selectedStudentId);
            $specialization = $student->department;

            // نبني استعلام الجداول بالتخصص + (term إن كان العمود موجودًا)
            $q = Timetable::query()->where('specialization', $specialization)->where('grade',$term);

            if (Schema::hasColumn('timetables', 'term')) {
                $q->where('term', $term); // فلترة بحسب الفصل الدراسي عند توفر العمود
            }

            $tables = $q->get();

            // استخراج المواد من الحصص 1..7 لكل الأيام ثم إزالة التكرار
            $subjects = $tables->flatMap(function ($t) {
                    return [
                        $t->period1, $t->period2, $t->period3, $t->period4,
                        $t->period5, $t->period6, $t->period7,
                    ];
                })
                ->filter(fn($s) => $s && trim($s) !== '')
                ->map(fn($s) => trim($s))
                ->unique()
                ->values();

            // نتائج الطالب الحالية لنفس الفصل/السنة لملء الحقول تلقائيًا
            $existing = StudentResult::where('student_id', $selectedStudentId)
                ->where('term', $term)
                ->where('year', $year)
                ->get()
                ->keyBy('subject');
        }

        return view('pages.inputs.results', compact(
            'students','subjects','existing','selectedStudentId','specialization','term','year','grade'
        ));
    }

    public function save(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required','exists:students,id'],
            'term'       => ['required','string','max:50'],
            'year'       => ['required','integer','min:2000','max:2100'],
            'marks'      => ['required','array'],           // marks[subject] => mark
            'marks.*'    => ['nullable','numeric','min:0','max:100'],
        ], [
            'student_id.required' => 'يجب اختيار الطالب.',
            'marks.required'      => 'لا توجد مواد لإدخال درجاتها.',
        ]);

        $student = Student::findOrFail($data['student_id']);
        $specialization = $student->department; // نعتمد تخصص الطالب فقط

        foreach ($data['marks'] as $subject => $mark) {
            if ($subject === null || trim($subject) === '') continue;
            $subject = trim($subject);
            $mark    = is_null($mark) || $mark === '' ? 0 : (float) $mark;

            StudentResult::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'subject'    => $subject,
                    'term'       => $data['term'],
                    'year'       => (int) $data['year'],
                ],
                [
                    'mark'           => $mark,
                    'specialization' => $specialization,
                ]
            );
        }

        return back()->with('success', 'تم حفظ/تحديث نتائج الطالب بنجاح ✅');
    }
}
