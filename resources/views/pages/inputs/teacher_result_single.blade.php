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
    <div class="col-lg-10">

      <div class="card glass-card border-0 shadow-sm mb-3">
        <div class="card-header bg-white" style="border-radius:22px 22px 0 0;">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-clipboard2-check"></i>
            <h5 class="mb-0 fw-semibold">إدخال درجة لطالب — حسب مقرر المعلّم</h5>
          </div>
        </div>

        <div class="card-body">
          @if(session('success')) <div class="alert alert-success text-center">{{ session('success') }}</div> @endif
          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
          @endif

          {{-- خطوة التحميل: نختار المعلم/المادة/الفصل/السنة والطالب --}}

            <form id="filterForm" class="row g-3" method="GET" action="{{ route('inputs.teacher_result.single.save') }}">

            <div class="col-md-5">
              <label class="form-label">المعلّم </label>
              @if(auth()->user()?->role == 'teacher' && $teacher)
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
                @foreach(['الأولى','الثانية','الثالثة'] as $t)
                  <option value="{{ $t }}" {{ ($term??'')===$t?'selected':'' }}>{{ $t }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-2">
              <label class="form-label">السنة</label>
              <input type="number" name="year" class="form-control" value="{{ $year }}" min="2000" max="2100">
            </div>

            <div class="col-md-6">
              <label class="form-label">الطالب</label>
              <select name="student_id" class="form-select" required>
                <option value="" disabled {{ $studentId?'':'selected' }}>اختر الطالب</option>
                @foreach(($students ?? collect()) as $stu)
                  <option value="{{ $stu->id }}" {{ (int)$studentId===(int)$stu->id?'selected':'' }}>
                    {{ $stu->student_name }} @if($stu->class_name) — {{ $stu->class_name }} @endif
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-12 d-flex gap-2">
              <button class="btn btn-outline-secondary">تحميل</button>
            </div>
          </form>

          {{-- خطوة الحفظ: نظهرها فقط عند اختيار طالب + مادة --}}
          @if($teacher && $subject && $studentId)
            <hr class="my-4">
            <form method="POST" action="{{ route('inputs.teacher_result.single.save') }}" class="row g-3">
              @csrf
              <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
              <input type="hidden" name="subject" value="{{ $subject }}">
              <input type="hidden" name="term" value="{{ $term }}">
              <input type="hidden" name="year" value="{{ $year }}">
              <input type="hidden" name="student_id" value="{{ $studentId }}">

              <div class="col-md-4">
                <label class="form-label">الدرجة (0–100)</label>
                <input type="number" name="mark" step="0.01" min="0" max="100"
                       class="form-control"
                       value="{{ old('mark', optional($existing)->mark) }}"
                       placeholder="0 - 100">
              </div>

              <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary">
                  <i class="bi bi-check2-circle me-1"></i> حفظ / تعديل الدرجة
                </button>
              </div>
            </form>
          @endif

        </div>
      </div>

    </div>
  </div>
</div>

@push('scripts')
<script>
(function(){
  const form = document.getElementById('filterForm');
  if(!form) return;

  // أي تغيير في هذه الحقول يعيد تحميل الصفحة ويجلب المواد والطلاب
  ['teacher_id','subject','term','year'].forEach(function(name){
    const el = form.querySelector(`[name="${name}"]`);
    if(el){
      el.addEventListener('change', function(){
        form.submit();
      });
    }
  });
})();
</script>
@endpush

@endsection
