<?php

namespace App\Http\Controllers\Inputs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentResult;

class ResultsViewController extends Controller
{
    public function student(Request $request)
    {
        $user = $request->user();

        // مدخلات الفلترة
        $year  = (int) $request->query('year', now()->year);
        $term  = $request->query('term'); // إن تُرك فارغًا نعرض كل الفصول
        $studentId = $request->query('student_id');

        // لو أدمن: أعرض قائمة الطلاب للاختيار
        $students = collect();
        if ($user->role === 'admin') {
            $students = Student::orderBy('student_name')->get(['id','student_name','class_name','department']);
        } else {
            // لو طالب: حدّد الطالب من العلاقة (user_id) ثم (national_id) كاحتياط
            $studentId = $this->resolveStudentId($user);
        }

        $student = null;
        $results = collect();
        $byTerm  = collect();

        if ($studentId) {
            $student = Student::find($studentId);

            $q = StudentResult::where('student_id', $studentId)
                ->where('year', $year)
                ->orderBy('term')
                ->orderBy('subject');

            if ($term) {
                $q->where('term', $term);
            }

            $results = $q->get();

            // تجميع حسب الفصل مع إحصائيات بسيطة
            $byTerm = $results->groupBy('term')->map(function ($items) {
                return [
                    'subjects' => $items->values(),
                    'count'    => $items->count(),
                    'total'    => round($items->sum('mark'), 2),
                    'avg'      => $items->count() ? round($items->avg('mark'), 2) : 0,
                ];
            });
        }

        return view('pages.results.student', compact(
            'students','student','studentId','results','byTerm','term','year','user'
        ));
    }

    private function resolveStudentId($user): ?int
    {
        // المحاولة بالأولوية: user_id ثم national_id
        $s = Student::where('user_id', $user->id)->first();
        if ($s) return $s->id;

        if (!empty($user->national_id)) {
            $s2 = Student::where('national_id', $user->national_id)->first();
            if ($s2) return $s2->id;
        }
        return null;
    }
}
