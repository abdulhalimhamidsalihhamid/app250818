{{-- resources/views/certificates/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card glass-card border-0 shadow-sm">
                <div class="card-header bg-white rounded-top-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-award"></i> شهادة رقم: {{ $cert->code }}
                    </h5>
                    <a href="{{ route('certificates.pdf',$cert->code) }}" class="btn btn-sm btn-success">
                        <i class="bi bi-download"></i> تحميل PDF
                    </a>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">اسم الطالب</dt>
                        <dd class="col-sm-8">{{ $cert->student_name }}</dd>

                        <dt class="col-sm-4">الصف</dt>
                        <dd class="col-sm-8">{{ $cert->class_name }}</dd>

                        <dt class="col-sm-4">القسم</dt>
                        <dd class="col-sm-8">{{ $cert->department }}</dd>

                        <dt class="col-sm-4">الدور</dt>
                        <dd class="col-sm-8">{{ $cert->round_name }}</dd>

                        <dt class="col-sm-4">رقم الجلوس</dt>
                        <dd class="col-sm-8">{{ $cert->seat_no }}</dd>

                        <dt class="col-sm-4">العام الدراسي</dt>
                        <dd class="col-sm-8">{{ $cert->academic_year }}</dd>

                        <dt class="col-sm-4">صفة القيد</dt>
                        <dd class="col-sm-8">{{ $cert->grade_of_year }}</dd>

                        <dt class="col-sm-4">التقدير العام</dt>
                        <dd class="col-sm-8">{{ $cert->general_remark }}</dd>

                        <dt class="col-sm-4">المجموع</dt>
                        <dd class="col-sm-8">{{ $cert->total_marks }}</dd>

                        <dt class="col-sm-4">النسبة</dt>
                        <dd class="col-sm-8">{{ $cert->percentage }}</dd>

                        <dt class="col-sm-4">تاريخ الإصدار</dt>
                        <dd class="col-sm-8">{{ $cert->issue_date }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
