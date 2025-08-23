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
    .avatar {
        width: 56px; height: 56px; border-radius: 50%; object-fit: cover; border: 1px solid #e9ecef;
    }
</style>
@endpush

@section('content')
<div class="container py-4" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-lg-11">

            {{-- إضافة موظف --}}
            <div class="card glass-card border-0 shadow-sm">
                <div class="card-header bg-white" style="border-radius:22px 22px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-plus-fill"></i>
                        <h5 class="mb-0 fw-semibold">إضافة موظف</h5>
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

                    <form method="POST" action="{{ route('inputs.staff.store') }}" enctype="multipart/form-data" class="row g-3">
                        @csrf

                        <div class="col-md-6">
                            <label class="form-label">اسم الموظف</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">الرقم الوطني</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                <input type="text" name="national_id" class="form-control" value="{{ old('national_id') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">البريد الإلكتروني</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="user@example.com">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">الهاتف</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">المسمّى الوظيفي</label>
                            <input type="text" name="job_title" class="form-control" value="{{ old('job_title') }}" placeholder="مثال: معلم رياضيات">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">القسم</label>
                            <input type="text" name="department" class="form-control" value="{{ old('department') }}" placeholder="إداري/تعليمي/تقني...">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">تاريخ التعيين</label>
                            <input type="date" name="hire_date" class="form-control" value="{{ old('hire_date') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">الراتب</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-cash-coin"></i></span>
                                <input type="number" step="0.01" name="salary" class="form-control" value="{{ old('salary') }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">الحالة</label>
                            <select name="status" class="form-select" required>
                                @foreach(['نشط','موقوف','منتهٍ'] as $st)
                                    <option value="{{ $st }}" {{ old('status','نشط')===$st?'selected':'' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">العنوان</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">صورة (اختياري)</label>
                            <input type="file" name="avatar" class="form-control" accept="image/*">
                            <small class="text-muted d-block mt-1">الحد الأقصى 5MB.</small>
                        </div>

                        <div class="col-12 d-flex gap-2">
                            <button class="btn btn-primary">
                                <i class="bi bi-check2-circle me-1"></i> حفظ
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">مسح</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- قائمة الموظفين --}}
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white d-flex align-items-center gap-2">
                    <i class="bi bi-people-fill"></i>
                    <span class="fw-semibold">الموظفون</span>
                </div>
                <div class="card-body">
                    @if(isset($staff) && $staff->count())
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>الصورة</th>
                                        <th>الاسم</th>
                                        <th>الرقم الوطني</th>
                                        <th>الهاتف</th>
                                        <th>القسم</th>
                                        <th>الحالة</th>
                                        <th class="text-center">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($staff as $s)
                                        <tr>
                                            <td>
                                                @if($s->avatar_path)
                                                    <img src="{{ asset('storage/'.$s->avatar_path) }}" class="avatar" alt="avatar">
                                                @else
                                                    <div class="avatar d-inline-flex align-items-center justify-content-center bg-light">
                                                        <i class="bi bi-person text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="fw-semibold">{{ $s->name }}</td>
                                            <td>{{ $s->national_id }}</td>
                                            <td>{{ $s->phone ?? '-' }}</td>
                                            <td>{{ $s->department ?? '-' }}</td>
                                            <td>
                                                <span class="badge text-bg-{{ $s->status==='نشط' ? 'success' : ($s->status==='موقوف' ? 'warning' : 'secondary') }}">
                                                    {{ $s->status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('inputs.staff.edit', $s) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil-square"></i> تعديل
                                                </a>
                                                <form action="{{ route('inputs.staff.destroy', $s) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('تأكيد حذف الموظف؟');">
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
                            {{ $staff->links() }}
                        </div>
                    @else
                        <div class="alert alert-info mb-0">لا توجد سجلات موظفين بعد.</div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
