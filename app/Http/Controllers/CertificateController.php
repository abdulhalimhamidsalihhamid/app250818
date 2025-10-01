<?php

// app/Http/Controllers/CertificateController.php
namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Mpdf\HTMLParserMode;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{

public function create(Request $request)
{
    // جلب الطلاب لعرضهم في القائمة المنسدلة
    $students = \App\Models\Student::select(
        'id','student_name','student_number','department','class_name',
        'email','dob','national_id','phone','gender','enrollment_date',
        'blood_type','address','guardian_name','guardian_phone'
    )->orderBy('student_name')->get();

    // جلب الشهادات مع إمكانية البحث والـ pagination
    $certificates = \App\Models\Certificate::when($request->q, function($q) use ($request) {
            $kw = trim($request->q);
            $q->where('student_name','like',"%{$kw}%")
              ->orWhere('seat_no','like',"%{$kw}%")
              ->orWhere('code','like',"%{$kw}%")
              ->orWhere('academic_year','like',"%{$kw}%");
        })
        ->latest('issue_date')
        ->paginate(10);

    // تمرير الطلاب + الشهادات إلى نفس الصفحة create.blade.php
    return view('certificates.create', compact('students','certificates'));
}



    public function store(Request $request) {
        $data = $request->validate([
            'student_name'   => 'required|string|max:255',
            'class_name'     => 'nullable|string|max:255',
            'department'     => 'nullable|string|max:255',
            'round_name'     => 'nullable|string|max:255',
            'seat_no'        => 'nullable|string|max:50',
            'academic_year'  => 'required|string|max:50',
            'grade_of_year'  => 'required|string|max:50',
            'general_remark' => 'nullable|string|max:255',
            'total_marks'    => 'nullable|string|max:50',
            'percentage'     => 'nullable|string|max:50',
            'issue_date'     => 'nullable|date',
        ]);

        // رقم مميز 6 أرقام
        do {
            $data['code'] = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (Certificate::where('code',$data['code'])->exists());

        $cert = Certificate::create($data);

        return redirect()->route('certificates.show', $cert->code)
            ->with('success','تم حفظ الشهادة ورقمها المميز: '.$cert->code);
    }

    public function show($code)
    {
        $cert = Certificate::where('code', $code)->firstOrFail();
        return view('certificates.show', compact('cert'));
    }

public function pdf($code)
{
    $cert = \App\Models\Certificate::where('code', $code)->firstOrFail();

    $html = view('certificates.academic_level_pdf', compact('cert'))->render();

    if (!is_dir(storage_path('app/mpdf_tmp'))) {
        mkdir(storage_path('app/mpdf_tmp'), 0775, true);
    }

    $mpdf = new Mpdf([
        'mode'               => 'utf-8',
        'format'             => 'A4',
        'default_font'       => 'dejavusans',
        'tempDir'            => storage_path('app/mpdf_tmp'),
        'autoScriptToLang'   => true,
        'autoLangToFont'     => true,
        'useSubstitutions'   => true,
        'simpleTables'       => true,      // مهم
        'useKerning'         => false,
        'tabSpaces'          => 0,
        'keep_table_proportions' => true,
        'shrink_tables_to_fit'   => 1,
    ]);

    $mpdf->SetDirectionality('rtl');

    // ملاحظة: استدعاء واحد فقط لملف HTML الذي بداخله الستايل
    $mpdf->WriteHTML($html, HTMLParserMode::DEFAULT_MODE);

    return $mpdf->Output("certificate_{$cert->code}.pdf", 'I');
}

public function search(Request $request)
    {
        $query = Certificate::query();

        // لو فيه كلمة بحث
        if ($request->filled('keyword')) {
            $query->where('student_name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('seat_no', 'like', '%' . $request->keyword . '%')
                  ->orWhere('student_id', $request->keyword);
        }

        $certificates = $query->latest()->paginate(10);

        return view('certificates.search', compact('certificates'));
    }

       public function destroy($id)
    {
        Certificate::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف الشهادة بنجاح.');
    }

}
