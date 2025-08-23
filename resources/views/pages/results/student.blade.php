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
@php
  $isAdmin = ($user->role ?? null) === 'admin';
@endphp

<div class="container py-4" dir="rtl">
  <div class="row justify-content-center">
    <div class="col-lg-11">

      <div class="card glass-card border-0 shadow-sm mb-3">
        <div class="card-header bg-white" style="border-radius:22px 22px 0 0;">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-clipboard2-data"></i>
            <h5 class="mb-0 fw-semibold">عرض نتيجة الطالب</h5>
          </div>
        </div>

        <div class="card-body">
          @if(session('success')) <div class="alert alert-success text-center">{{ session('success') }}</div> @endif
          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
          @endif

          {{-- فورم التحميل/الفلترة --}}
          <form class="row g-3" method="GET" action="{{ route('results.student') }}">
            @if(auth()->user()?->role == 'admin' || auth()->user()?->role == 'teacher' || auth()->user()?->role == 'staff' )
              <div class="col-md-6">
                <label class="form-label">اسم الطالب</label>
                <select name="student_id" class="form-select" required>
                  <option value="" disabled {{ $studentId ? '' : 'selected' }}>اختر الطالب</option>
                  @foreach($students as $stu)
                    <option value="{{ $stu->id }}" {{ (int)$studentId === (int)$stu->id ? 'selected' : '' }}>
                      {{ $stu->student_name }} @if($stu->class_name) — {{ $stu->class_name }} @endif
                    </option>
                  @endforeach
                </select>
              </div>
            @else
              <div class="col-md-6">
                <label class="form-label">اسم الطالب</label>
                <input type="text" class="form-control"
                       value="{{ $student ? ($student->student_name.($student->class_name ? ' — '.$student->class_name : '')) : '—' }}"
                       disabled>
                <input type="hidden" name="student_id" value="{{ $studentId }}">
              </div>
            @endif

            <div class="col-md-3">
              <label class="form-label">الفصل الدراسي</label>
              <select name="term" class="form-select">
                <option value="" {{ empty($term) ? 'selected' : '' }}>(كل الفصول)</option>
                @foreach(['الأولى','الثانية','الثالثة'] as $t)
                  <option value="{{ $t }}" {{ ($term ?? '') === $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-2">
              <label class="form-label">السنة</label>
              <input type="number" name="year" class="form-control" value="{{ $year }}" min="2000" max="2100">
            </div>

            <div class="col-md-1 d-flex align-items-end">
              <button class="btn btn-outline-secondary w-100">
                تحميل
              </button>
            </div>
          </form>

        </div>
      </div>

      {{-- نتائج الطالب --}}
      @if($studentId && ($results->count() > 0))
        @if(empty($term))
          {{-- عرض مجمّع لكل فصل --}}
          @foreach($byTerm as $t => $info)
            <div class="card border-0 shadow-sm mb-3">
              <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                  <i class="bi bi-file-earmark-text"></i>
                  <strong>{{ $student->student_name }}</strong>
                  <span class="text-muted">— {{ $t }} / {{ $year }}</span>
                </div>
                <div class="small text-muted">
                  عدد المواد: {{ $info['count'] }} |
                  المجموع: {{ $info['total'] }} |
                  المتوسط: {{ $info['avg'] }}
                </div>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table mb-0 align-middle">
                    <thead class="table-light">
                      <tr>
                        <th style="width:60px">#</th>
                        <th>المادة</th>
                        <th style="width:160px">الدرجة</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($info['subjects'] as $i => $row)
                        <tr>
                          <td>{{ $i+1 }}</td>
                          <td>{{ $row->subject }}</td>
                          <td>{{ number_format($row->mark, 2) }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          @endforeach
        @else
          {{-- عرض فصل محدد --}}
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
              <div>
                <i class="bi bi-file-earmark-text"></i>
                <strong>{{ $student->student_name }}</strong>
                <span class="text-muted">— {{ $term }} / {{ $year }}</span>
              </div>
              @php
                $count = $results->count();
                $total = round($results->sum('mark'),2);
                $avg   = $count ? round($results->avg('mark'),2) : 0;
              @endphp
              <div class="small text-muted">
                عدد المواد: {{ $count }} |
                المجموع: {{ $total }} |
                المتوسط: {{ $avg }}
              </div>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table mb-0 align-middle">
                  <thead class="table-light">
                    <tr>
                      <th style="width:60px">#</th>
                      <th>المادة</th>
                      <th style="width:160px">الدرجة</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($results as $i => $row)
                      <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $row->subject }}</td>
                        <td>{{ number_format($row->mark, 2) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        @endif
      @elseif($studentId)
        <div class="alert alert-warning">
          لا توجد نتائج لهذا الطالب في {{ $term ? $term.' / ' : '' }}{{ $year }}.
        </div>
      @else
        @if(!$isAdmin)
          <div class="alert alert-danger">
            لم يتم ربط حسابك بسجل طالب. يرجى مراجعة الإدارة.
          </div>
        @endif
      @endif

    </div>
  </div>
</div>
@endsection
