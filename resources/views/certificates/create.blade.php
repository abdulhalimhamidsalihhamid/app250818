{{-- resources/views/certificates/create.blade.php --}}
@extends('layouts.app')

@push('styles')
<style>
    .glass-card{
        background: rgba(255,255,255,.35);
        border: 1px solid rgba(255,255,255,.55);
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(0,0,0,.10);
        backdrop-filter: blur(10px);
    }
</style>
@endpush

@section('content')
<div class="container py-4" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card glass-card border-0 shadow-sm">
                <div class="card-header bg-white rounded-top-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-plus"></i>
                        <h5 class="mb-0 fw-semibold">إضافة شهادة بالمستوى الدراسي</h5>
                    </div>
                </div>

                <div class="card-body">

                    {{-- أخطاء التحقق --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <div class="fw-bold mb-1">تحقّق من الحقول التالية:</div>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('certificates.store') }}" class="row g-3">
                        @csrf

                        {{-- اختيار الطالب من جدول students --}}
                        <div class="col-12">
                            <label class="form-label">اختر الطالب من السجل</label>
                            <select id="pick_student" class="form-select">
                                <option value="" selected>— اختر الطالب —</option>
                                @foreach($students as $s)
                                    <option
                                        value="{{ $s->id }}"
                                        data-name="{{ $s->student_name }}"
                                        data-classname="{{ $s->class_name  }}"
                                        data-department="{{ $s->department === 'science' ? 'علمي' : 'أدبي' }}"
                                        data-seat="{{ $s->student_number }}" {{-- هنا نستخدم student_number كرقم جلوس --}}
                                    >
                                        {{ $s->student_name }} — {{ $s->student_number }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">عند اختيار الطالب سيتم تعبئة الحقول تلقائيًا، ويمكنك تعديلها قبل الحفظ.</small>
                        </div>

                        <input type="hidden" name="student_id" id="student_id" value="{{ old('student_id') }}">

                        <div class="col-md-12">
                            <label class="form-label">اسم الطالب <span class="text-danger">*</span></label>
                            <input type="text" name="student_name" class="form-control"
                                   value="{{ old('student_name') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">الصف</label>
                            <input type="text" name="class_name" class="form-control"
                                            value="{{ old('class_name') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">القسم</label>
                            <input type="text" name="department" class="form-control"
                                   value="{{ old('department') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">الدور</label>
                            <input type="text" name="round_name" class="form-control"
                                   value="{{ old('round_name') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">رقم الجلوس</label>
                            <input type="text" name="seat_no" class="form-control"
                                   value="{{ old('seat_no') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">العام الدراسي <span class="text-danger">*</span></label>
                            <input type="text" name="academic_year" class="form-control"
                                   value="{{ old('academic_year') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">صفة القيد <span class="text-danger">*</span></label>
                            <input type="text" name="grade_of_year" class="form-control"
                                   value="{{ old('grade_of_year') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">التقدير العام</label>
                            <input type="text" name="general_remark" class="form-control"
                                   value="{{ old('general_remark') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">المجموع</label>
                            <input type="text" name="total_marks" class="form-control"
                                   value="{{ old('total_marks') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">النسبة %</label>
                            <input type="text" name="percentage" class="form-control"
                                   value="{{ old('percentage') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">تاريخ الإصدار</label>
                            <input type="date" name="issue_date" class="form-control"
                                   value="{{ old('issue_date', date('Y-m-d')) }}">
                        </div>

                        <div class="col-12 d-flex gap-2">
                            <button class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> حفظ الشهادة
                            </button>
                            <a href="{{ route('certificates.search') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-search"></i> بحث عن شهادة
                            </a>
                        </div>
                    </form>

                </div>
            </div>
{{-- === قائمة الشهادات أسفل الفورم === --}}
<div class="card glass-card border-0 shadow-sm mt-4">
  <div class="card-header bg-white rounded-top-3 d-flex align-items-center justify-content-between">
    <h6 class="mb-0 fw-semibold">قائمة الشهادات المستخرجة</h6>

    <form method="GET" action="{{ route('certificates.create') }}" class="d-flex gap-2">
      <input type="text" name="q" class="form-control form-control-sm"
             placeholder="ابحث باسم الطالب/رقم الجلوس/الكود/العام…"
             value="{{ request('q') }}">
      <button class="btn btn-sm btn-primary">بحث</button>
      <a href="{{ route('certificates.create') }}" class="btn btn-sm btn-outline-secondary">مسح</a>
    </form>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover align-middle text-center">
        <thead class="table-light">
          <tr>
            <th>الكود</th>
            <th>الطالب</th>
            <th>رقم الجلوس</th>
            <th>القسم</th>
            <th>الصف</th>
            <th>العام الدراسي</th>
            <th>تاريخ الإصدار</th>
            <th style="width:240px">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          @forelse($certificates as $c)
            <tr>
              <td><span class="badge bg-secondary">{{ $c->code }}</span></td>
              <td class="text-start">{{ $c->student_name }}</td>
              <td>{{ $c->seat_no }}</td>
              <td>{{ $c->department }}</td>
              <td>{{ $c->class_name }}</td>
              <td>{{ $c->academic_year }}</td>
              <td>{{ $c->issue_date }}</td>
              <td>
                <div class="d-flex justify-content-center gap-2">
                  <a class="btn btn-sm btn-outline-primary"
                     href="{{ route('certificates.show', $c->code) }}">
                    عرض
                  </a>

                  <a class="btn btn-sm btn-success" target="_blank"
                     href="{{ route('certificates.pdf', $c->code) }}">
                    <i class="bi bi-printer"></i> طباعة
                  </a>

                  <form method="POST"
                        action="{{ route('certificates.destroy', $c->id) }}"
                        class="d-inline"
                        onsubmit="return confirm('حذف هذه الشهادة نهائيًا؟');">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">حذف</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-muted">لا توجد شهادات حتى الآن.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- ترقيم الصفحات --}}
    @if(method_exists($certificates, 'links'))
      {{ $certificates->withQueryString()->links() }}
    @endif
  </div>
</div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sel = document.getElementById('pick_student');
    if (!sel) return;

    sel.addEventListener('change', function () {
        const opt = sel.options[sel.selectedIndex];
        if (!opt || !opt.value) return;

        document.getElementById('student_id').value = opt.value;

        const fill = (name, val) => {
            const el = document.querySelector(`[name="${name}"]`);
            if (el) el.value = val || '';
        };

        fill('student_name', opt.dataset.name);
        fill('class_name', opt.dataset.classname);
        fill('department',   opt.dataset.department);
        fill('seat_no',      opt.dataset.seat); // seat_no مرتبط بـ student_number
    });
});
</script>
@endpush
