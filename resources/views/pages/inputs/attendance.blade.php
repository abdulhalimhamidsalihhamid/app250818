@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .glass-card{
        background: rgba(255,255,255,.35);
        border: 1px solid rgba(255,255,255,.55);
        border-radius: 22px;
        box-shadow: 0 8px 24px rgba(0,0,0,.10);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
</style>
@endpush

@section('content')
<div class="container py-4" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-lg-11">

            {{-- إدخال الحضور/الغياب --}}
            <div class="card glass-card border-0 shadow-sm">
                <div class="card-header bg-white" style="border-radius:22px 22px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check2-square"></i>
                        <h5 class="mb-0 fw-semibold">إدخال الحضور والغياب</h5>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success text-center">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <div class="fw-bold mb-1">تحقق من الحقول التالية:</div>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('inputs.attendance.store') }}" class="row g-3">
                        @csrf
                        {{-- الدور --}}
                        <div class="col-md-3">
                            <label class="form-label">الفئة</label>
                            <select id="role" name="role" class="form-select" required>
                                <option value="" disabled {{ empty($role) ? 'selected':'' }}>اختر</option>
                                <option value="student" {{ $role==='student'?'selected':'' }}>طالب</option>
                                <option value="teacher" {{ $role==='teacher'?'selected':'' }}>معلم</option>
                                <option value="staff"   {{ $role==='staff'  ?'selected':'' }}>موظف</option>
                            </select>
                        </div>

                        {{-- الشخص بحسب الدور --}}
                        <div class="col-md-5">
                            <label class="form-label">الاسم</label>
                            <select id="person_id" name="person_id" class="form-select" required>
                                <option value="" disabled {{ empty($person_id) ? 'selected':'' }}>اختر الاسم</option>

                                {{-- طلاب --}}
                                @if($role==='student')
                                    @foreach($students as $s)
                                        <option value="{{ $s->id }}" {{ (int)$person_id===(int)$s->id?'selected':'' }}>
                                            {{ $s->student_name }}
                                        </option>
                                    @endforeach
                                @elseif($role==='teacher') {{-- معلمين --}}
                                    @foreach($teachers as $t)
                                        <option value="{{ $t->id }}" {{ (int)$person_id===(int)$t->id?'selected':'' }}>
                                            {{ $t->name }}
                                        </option>
                                    @endforeach
                                @elseif($role==='staff') {{-- موظفين --}}
                                    @foreach($staff as $st)
                                        <option value="{{ $st->id }}" {{ (int)$person_id===(int)$st->id?'selected':'' }}>
                                            {{ $st->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <small class="text-muted">إن تغيّر نوع الفئة، حدّث الصفحة أو اخترها ثم أعد فتح القائمة.</small>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">التاريخ</label>
                            <input type="date" name="date" class="form-control" value="{{ $date }}" required>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">الحالة</label>
                            <select name="status" class="form-select" required>
                                @foreach(['حاضر','غائب','متأخر','مأذون'] as $st)
                                    <option value="{{ $st }}">{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">الفصل الدراسي</label>
                            <select name="term" class="form-select">
                                @foreach (['الفصل الأول','الفصل الثاني'] as $t)
                                    <option value="{{ $t }}" {{ $term===$t?'selected':'' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">السنة</label>
                            <input type="number" name="year" class="form-control" value="{{ $year }}" min="2000" max="2100">
                        </div>

                        <div class="col-md-7">
                            <label class="form-label">ملاحظات</label>
                            <input type="text" name="notes" class="form-control" placeholder="اختياري">
                        </div>

                        <div class="col-12 d-flex gap-2">
                            <button class="btn btn-primary">
                                <i class="bi bi-check2-circle me-1"></i> حفظ / تعديل
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- عرض السجلات --}}
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white d-flex align-items-center gap-2">
                    <i class="bi bi-list-ul"></i>
                    <span class="fw-semibold">سجلات الحضور</span>
                    {{-- فلاتر سريعة --}}
                    <form method="GET" action="{{ route('inputs.attendance') }}" class="ms-auto d-flex gap-2 align-items-center">
                        <select name="role" class="form-select form-select-sm" style="width:140px">
                            <option value="">الكل</option>
                            <option value="student" {{ ($role??'')==='student'?'selected':'' }}>طلاب</option>
                            <option value="teacher" {{ ($role??'')==='teacher'?'selected':'' }}>معلمون</option>
                            <option value="staff"   {{ ($role??'')==='staff'  ?'selected':'' }}>موظفون</option>
                        </select>
                        <input type="date" name="from" class="form-control form-control-sm" value="{{ $from }}">
                        <input type="date" name="to"   class="form-control form-control-sm" value="{{ $to }}">
                        <button class="btn btn-sm btn-outline-secondary">تصفية</button>
                    </form>
                </div>
                <div class="card-body">
                    @if(isset($records) && $records->count())
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>التاريخ</th>
                                        <th>الفئة</th>
                                        <th>الاسم</th>
                                        <th>الحالة</th>
                                        <th>الفصل/السنة</th>
                                        <th>ملاحظات</th>
                                        <th class="text-center">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($records as $r)
                                        <tr>
                                            <td>{{ optional($r->date)->format('Y-m-d') }}</td>
                                            <td>
                                                @if($r->role==='student') طالب
                                                @elseif($r->role==='teacher') معلم
                                                @else موظف
                                                @endif
                                            </td>
                                            <td>
                                                {{-- إظهار الاسم بإيجاد السجل حسب الدور (عرض فقط) --}}
                                                @php
                                                    $name = '-';
                                                    if ($r->role==='student') {
                                                        $obj = $students->firstWhere('id', $r->person_id);
                                                        $name = $obj->student_name ?? '-';
                                                    } elseif ($r->role==='teacher') {
                                                        $obj = $teachers->firstWhere('id', $r->person_id);
                                                        $name = $obj->name ?? '-';
                                                    } else {
                                                        $obj = $staff->firstWhere('id', $r->person_id);
                                                        $name = $obj->name ?? '-';
                                                    }
                                                @endphp
                                                {{ $name }}
                                            </td>
                                            <td>
                                                <span class="badge text-bg-{{ $r->status==='حاضر'?'success':($r->status==='غائب'?'danger':($r->status==='متأخر'?'warning':'secondary')) }}">
                                                    {{ $r->status }}
                                                </span>
                                            </td>
                                            <td>{{ ($r->term ?? '-') }} / {{ ($r->year ?? '-') }}</td>
                                            <td>{{ $r->notes ?? '-' }}</td>
                                            <td class="text-center">
                                                {{-- لإعادة التعديل: افتح الصفحة مع تمرير الدور/الشخص/التاريخ لتعبئة النموذج --}}
                                                <a href="{{ route('inputs.attendance', ['role'=>$r->role,'person_id'=>$r->person_id,'date'=>optional($r->date)->format('Y-m-d'),'term'=>$r->term,'year'=>$r->year]) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil-square"></i> تعديل
                                                </a>
                                                <form method="POST" action="{{ route('inputs.attendance.destroy', $r) }}" class="d-inline"
                                                      onsubmit="return confirm('حذف السجل؟');">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i> حذف
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $records->links() }}
                        </div>
                    @else
                        <div class="alert alert-info mb-0">لا توجد سجلات حتى الآن.</div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
(function(){
  // نحضّر القوائم من السيرفر كـ JSON
  const students = @json(($students ?? collect())->map(fn($s)=>['id'=>$s->id,'name'=>$s->student_name])->values());
  const teachers = @json(($teachers ?? collect())->map(fn($t)=>['id'=>$t->id,'name'=>$t->name])->values());
  const staff    = @json(($staff    ?? collect())->map(fn($u)=>['id'=>$u->id,'name'=>$u->name])->values());

  const roleSel   = document.getElementById('role');
  const personSel = document.getElementById('person_id');

  function optionsFor(role){
    if(role==='student') return students;
    if(role==='teacher') return teachers;
    if(role==='staff')   return staff;
    return [];
  }

  function fillPersons(role, keepId=null){
    const list = optionsFor(role);
    // افرغ القائمة وأضف العنصر الافتراضي
    personSel.innerHTML = '';
    const ph = document.createElement('option');
    ph.value = '';
    ph.disabled = true;
    ph.selected = true;
    ph.textContent = 'اختر الاسم';
    personSel.appendChild(ph);

    // أضف الأسماء
    for(const it of list){
      const opt = document.createElement('option');
      opt.value = it.id;
      opt.textContent = it.name;
      personSel.appendChild(opt);
    }

    // إن وُجد ID سابق حاول اختياره
    if(keepId){
      personSel.value = String(keepId);
    }
  }

  // عند تغيير الفئة
  roleSel?.addEventListener('change', function(){
    fillPersons(this.value);
  });

  // ملء أولي عند تحميل الصفحة (لو فيه قيمة قديمة للفئة/الشخص)
  document.addEventListener('DOMContentLoaded', function(){
    const currentRole = roleSel?.value || '';
    const currentId   = "{{ (string)($person_id ?? '') }}";
    if(currentRole){
      fillPersons(currentRole, currentId || null);
    }
  });
})();
</script>
@endpush

@endsection
