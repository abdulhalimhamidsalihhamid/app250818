<?php

namespace App\Http\Controllers\Inputs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\StudentResult;

class TeacherResultsController extends Controller
{
    // دوال توافقية حتى تعمل أي Routes قديمة تشير إلى index/store
public function index(\Illuminate\Http\Request $request)
{
    return $this->single($request);
}

public function store(\Illuminate\Http\Request $request)
{
    return $this->singleStore($request);
}

    /** نفك الحقل subject لو فيه أكثر من مادة مفصولة بفواصل أو شرطات */
    private function parseSubjects(?string $raw)
    {
        if (!$raw) return collect();
        $parts = preg_split('/[,\/\|\-\؛\،]+/u', $raw) ?: [];
        return collect($parts)
            ->map(fn($s) => trim($s))
            ->filter(fn($s) => $s !== '')
            ->unique()
            ->values()
            ->sort();
    }

    /** نموذج إدخال درجة لطالب واحد */
    public function single(Request $request)
    {
        $teachers = Teacher::orderBy('name')->get(['id','name','subject','department','national_id']);

        // اختيار افتراضي لو المستخدم معلّم
        $selectedTeacherId = $request->query('teacher_id');
        if (auth()->user()?->role === 'teacher' && !$selectedTeacherId) {
            $selectedTeacherId = optional(
                Teacher::where('national_id', auth()->user()->national_id)->first()
            )->id;
        }

        $teacher   = $selectedTeacherId ? Teacher::find($selectedTeacherId) : null;
        $subjects  = $this->parseSubjects(optional($teacher)->subject);
        $subject   = $request->query('subject');
        if (!$subject && $subjects->count() === 1) $subject = $subjects->first(); // مادة واحدة فقط

        $term      = $request->query('term', 'الفصل الأول');
        $year      = (int)$request->query('year', now()->year);

        // الطلاب: نرشّحهم حسب قسم المعلّم إن كان محددًا
        $students = collect();
        if ($teacher) {
            $students = Student::query()
                ->when($teacher->department, fn($q) => $q->where('department', $teacher->department))
                ->orderBy('student_name')
                ->get(['id','student_name','department','class_name']);
        }

        $studentId = $request->query('student_id');

        // الدرجة الحالية (إن وُجدت) لملء الحقل
        $existing = null;
        if ($studentId && $subject) {
            $existing = StudentResult::where('student_id', $studentId)
                ->where('subject', $subject)
                ->where('term', $term)
                ->where('year', $year)
                ->first();
        }

        return view('pages.inputs.teacher_result_single', compact(
            'teachers','teacher','selectedTeacherId',
            'subjects','subject',
            'students','studentId',
            'term','year','existing'
        ));
    }

    /** حفظ / تعديل الدرجة لطالب واحد */
    public function singleStore(Request $request)
    {
        // لو المستخدم معلّم نثبّت teacher_id تلقائيًا
        $teacherId = $request->input('teacher_id');
        if (auth()->user()?->role === 'teacher') {
            $teacherId = optional(
                Teacher::where('national_id', auth()->user()->national_id)->first()
            )->id;
        }

        $data = $request->validate([
            'teacher_id' => ['nullable','integer','exists:teachers,id'],
            'subject'    => ['required','string','max:150'],
            'student_id' => ['required','integer','exists:students,id'],
            'term'       => ['required', Rule::in(['الأولى','الثانية','الثالثة'])],
            'year'       => ['required','integer','min:2000','max:2100'],
            'mark'       => ['required','numeric','min:0','max:100'],
        ],[
            'subject.required' => 'اختر المادة.',
            'student_id.required' => 'اختر الطالب.',
        ]);

        $teacher = $teacherId ? Teacher::findOrFail($teacherId) : null;

        // إن كان المستخدم معلّم: تأكد أن المادة ضمن مواده
        if (auth()->user()?->role === 'teacher') {
            $allowed = $this->parseSubjects(optional($teacher)->subject);
            if ($allowed->isNotEmpty() && !$allowed->contains($data['subject'])) {
                return back()->withErrors(['subject' => 'هذه المادة ليست ضمن موادك التدريسية.'])->withInput();
            }
        }

        // (اختياري) لو عند المعلّم قسم محدد، تأكد الطالب من نفس القسم
        if ($teacher && $teacher->department) {
            $studentDept = optional(Student::find($data['student_id']))->department;
            if ($studentDept && $studentDept !== $teacher->department) {
                return back()->withErrors(['student_id' => 'الطالب ليس من نفس قسم المعلّم.'])->withInput();
            }
        }

        StudentResult::updateOrCreate(
            [
                'student_id' => (int)$data['student_id'],
                'subject'    => $data['subject'],
                'term'       => $data['term'],
                'year'       => (int)$data['year'],
            ],
            [
                'mark'           => (float)$data['mark'],
                'specialization' => $teacher->department ?? 'غير محدد',
            ]
        );

        return back()->with('success','تم حفظ الدرجة بنجاح ✅')
                     ->withInput(); // يبقي اختياراتك على الشاشة
    }
}
