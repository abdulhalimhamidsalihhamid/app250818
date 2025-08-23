@extends('layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
@endpush

@section('content')
@php
    $students          = $students ?? collect();
    $subjects          = $subjects ?? collect();
    $existing          = $existing ?? collect();
    $selectedStudentId = $selectedStudentId ?? null;
    $specialization    = $specialization ?? null;
    $term              = $term ?? 'الفصل الأول';
    $year              = $year ?? now()->year;
@endphp

<div class="container py-4" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-lg-11">

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white d-flex align-items-center gap-2">
                    <i class="bi bi-clipboard2-data"></i>
                    <span class="fw-semibold">إدخال نتيجة الطالب</span>
                </div>
                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success text-center">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <div class="fw-bold mb-1">تحقق من الحقول التالية:</div>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- اختيار الطالب لقراءة تخصصه وجلب المواد --}}
                    <form class="row g-3" method="GET" action="{{ route('inputs.results') }}">
                        <div class="col-md-6">
                            <label class="form-label">الطالب (من جدول الطلاب)</label>
                            <select name="student_id" class="form-select" required>
                                <option value="" disabled {{ $selectedStudentId ? '' : 'selected' }}>اختر الطالب</option>
                                @foreach ($students as $stu)
                                    <option value="{{ $stu->id }}" {{ (int)$selectedStudentId === (int)$stu->id ? 'selected':'' }}>
                                        {{ $stu->student_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">الفصل الدراسي</label>
                            <select name="term" class="form-select">
                                @foreach (['الأولى','الثانية','الثالثة'] as $t)
                                    <option value="{{ $t }}" {{ $term===$t ? 'selected':'' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">السنة</label>
                            <input type="number" name="year" class="form-control" value="{{ $year }}" min="2000" max="2100">
                        </div>

                        <div class="col-md-1 d-flex align-items-end">
                            <button class="btn btn-outline-secondary w-100">
                                تحميل المواد
                            </button>
                        </div>
                    </form>

                    @if($selectedStudentId)
                        <div class="mt-3">
                            <span class="badge text-bg-light">
                                تخصص الطالب: <strong>{{ $specialization ?? 'غير محدد' }}</strong>
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- نموذج الدرجات يظهر فقط عند توفر مواد --}}
            @if($selectedStudentId && $subjects->count())
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <span class="fw-semibold">الدرجات — {{ $term }} / {{ $year }}</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('inputs.results.save') }}">
                            @csrf
                            <input type="hidden" name="student_id" value="{{ $selectedStudentId }}">
                            <input type="hidden" name="term" value="{{ $term }}">
                            <input type="hidden" name="year" value="{{ $year }}">

                            <div class="row g-3">
                                @foreach ($subjects as $subj)
                                    @php $prev = optional($existing->get($subj))->mark; @endphp
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $subj }}</label>
                                        <input type="number" step="0.01" min="0" max="100"
                                               name="marks[{{ $subj }}]"
                                               class="form-control"
                                               value="{{ old('marks.'.$subj, $prev) }}"
                                               placeholder="أدخل درجة {{ $subj }}">
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex gap-2 mt-3">
                                <button class="btn btn-primary">
                                    <i class="bi bi-check2-circle me-1"></i> حفظ / تعديل
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @elseif($selectedStudentId)
                <div class="alert alert-warning mt-3">
                    لا توجد مواد مسجّلة لهذا التخصص (أو الصف) في الجداول.
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
