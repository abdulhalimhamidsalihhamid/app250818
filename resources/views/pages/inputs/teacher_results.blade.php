@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
.glass-card{
  background: rgba(255,255,255,.35);
  border:1px solid rgba(255,255,255,.55);
  border-radius:22px; box-shadow:0 8px 24px rgba(0,0,0,.10);
  backdrop-filter:blur(10px); -webkit-backdrop-filter:blur(10px);
}
</style>
@endpush

@section('content')
<div class="container py-4" dir="rtl">
  <div class="row justify-content-center">
    <div class="col-lg-11">

      <div class="card glass-card border-0 shadow-sm mb-3">
        <div class="card-header bg-white" style="border-radius:22px 22px 0 0;">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-journal-check"></i>
            <h5 class="mb-0 fw-semibold">إدخال درجات — حسب مقرر المعلّم</h5>
          </div>
        </div>

        <div class="card-body">
          @if(session('success')) <div class="alert alert-success text-center">{{ session('success') }}</div> @endif
          @if($errors->any())
            <div class="alert alert-danger">
              <div class="fw-bold mb-1">تحقق من الحقول:</div>
              <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
          @endif

          {{-- اختيار المعلّم/المادة/الفصل/السنة --}}
          <form class="row g-3" method="GET" action="{{ route('inputs.teacher_results') }}">
            <div class="col-md-5">
              <label class="form-label">المعلّم</label>
              @if(auth()->user()?->role === 'teacher' && $teacher)
                <input type="text" class="form-control" value="{{ $teacher->name }} — {{ $teacher->department ?? 'قسم غير محدد' }}" disabled>
                <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
              @else
                <select name="teacher_id" class="form-select" required>
                  <option value="" disabled {{ $selectedTeacherId?'':'selected' }}>اختر المعلّم</option>
                  @foreach($teachers as $t)
                    <option value="{{ $t->id }}" {{ (int)$selectedTeacherId===(int)$t->id?'selected':'' }}>
                      {{ $t->name }} — {{ $t->department ?? 'قسم غير محدد' }}
                    </option>
                  @endforeach
                </select>
              @endif
            </div>

            <div class="col-md-3">
              <label class="form-label">المادة</label>
              <select name="subject" class="form-select" required>
                <option value="" disabled {{ $subject?'':'selected' }}>اختر المادة</option>
                @foreach(($subjects ?? collect()) as $s)
                  <option value="{{ $s }}" {{ $subject===$s?'selected':'' }}>{{ $s }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-2">
              <label class="form-label">الفصل</label>
              <select name="term" class="form-select">
                @foreach(['الفصل الأول','الفصل الثاني'] as $t)
                  <option value="{{ $t }}" {{ ($term??'')===$t?'selected':'' }}>{{ $t }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-1">
              <label class="form-label">السنة</label>
              <input type="number" name="year" class="form-control" value="{{ $year }}" min="2000" max="2100">
            </div>

            <div class="col-md-1">
              <label class="form-label">الشعبة</label>
              <input type="text" name="class_name" class="form-control" value="{{ $class }}" placeholder="اختياري">
            </div>

            <div class="col-12 d-flex gap-2">
              <button class="btn btn-outline-secondary">تحميل الطلاب</button>
            </div>
          </form>
        </div>
      </div>

      {{-- جدول الطلاب + إدخال الدرجات --}}
      @if(($teacher && $subject) && ($students??collect())->count())
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white d-flex align-items-center gap-2">
            <i class="bi bi-people"></i>
            <span class="fw-semibold">الطلاب — {{ $teacher->department ?? 'قسم غير محدد' }} / مادة: {{ $subject }} / {{ $term }} {{ $year }}</span>
          </div>
          <div class="card-body">
            <form method="POST" action="{{ route('inputs.teacher_results.save') }}">
              @csrf
              <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
              <input type="hidden" name="subject" value="{{ $subject }}">
              <input type="hidden" name="term" value="{{ $term }}">
              <input type="hidden" name="year" value="{{ $year }}">

              <div class="table-responsive">
                <table class="table align-middle">
                  <thead class="table-light">
                    <tr>
                      <th style="width:60px">#</th>
                      <th>اسم الطالب</th>
                      <th>الشعبة</th>
                      <th style="width:160px">الدرجة (0–100)</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($students as $i => $stu)
                      @php $prev = optional($existing->get($stu->id))->mark; @endphp
                      <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $stu->student_name }}</td>
                        <td>{{ $stu->class_name ?? '-' }}</td>
                        <td>
                          <input type="number" step="0.01" min="0" max="100"
                                 name="marks[{{ $stu->id }}]"
                                 class="form-control"
                                 value="{{ old('marks.'.$stu->id, $prev) }}"
                                 placeholder="0 - 100">
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

              <div class="mt-3 d-flex gap-2">
                <button class="btn btn-primary">
                  <i class="bi bi-check2-circle me-1"></i> حفظ الدرجات
                </button>
              </div>
            </form>
          </div>
        </div>
      @elseif($teacher && $subject)
        <div class="alert alert-warning">لا يوجد طلاب مطابقون لقسم المعلّم{{ $class ? " / الشعبة ($class)" : '' }}.</div>
      @endif

    </div>
  </div>
</div>
@endsection
