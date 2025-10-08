<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Timetable;

class TimetableController extends Controller
{
 public function index(Request $request)
{
    // نتحقق من قيم الفلاتر (اختيارية)
    $filters = $request->validate([
        'specialization' => ['nullable', Rule::in(['علمي','أدبي'])],
        'grade'          => ['nullable', Rule::in(['الأولى','الثانية','الثالثة'])],
    ]);

    $query = Timetable::query();

    if (!empty($filters['specialization'])) {
        $query->where('specialization', $filters['specialization']);
    }
    if (!empty($filters['grade'])) {
        $query->where('grade', $filters['grade']);
    }

    $timetables = $query->latest()->get();

    // نمرر المتغيرات للفيو حتى تستخدمها الـBlade بأمان
    return view('pages.inputs.timetables', [
        'timetables'     => $timetables,
        'specialization' => $filters['specialization'] ?? null,
        'grade'          => $filters['grade'] ?? null,
    ]);
}


    public function store (Request $request)
{
   $validated = $request->validate([
        'day'            => ['required', Rule::in(['الأحد','الإثنين','الثلاثاء','الأربعاء','الخميس'])],
        'specialization' => ['required', Rule::in(['general','science','literature'])],
        'grade'          => ['required', Rule::in(['الأولى','الثانية','الثالثة'])],
        'period1' => ['required','string','max:120'],
        'period2' => ['required','string','max:120'],
        'period3' => ['required','string','max:120'],
        'period4' => ['required','string','max:120'],
        'period5' => ['required','string','max:120'],
        'period6' => ['required','string','max:120'],
        'period7' => ['required','string','max:120'],
    ]);

    Timetable::updateOrCreate(
        [
            'day'            => $validated['day'],
            'specialization' => $validated['specialization'],
            'grade'          => $validated['grade'],
        ],
        $validated
    );

    return back()->with('success', 'تم الحفظ / التعديل بنجاح ✅');
}



}
