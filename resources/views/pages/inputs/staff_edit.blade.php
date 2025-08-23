@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .glass-card{background:rgba(255,255,255,.35);border:1px solid rgba(255,255,255,.55);border-radius:22px;box-shadow:0 8px 24px rgba(0,0,0,.10);backdrop-filter:blur(10px)}
    .avatar { width: 72px; height: 72px; border-radius: 50%; object-fit: cover; border: 1px solid #e9ecef; }
</style>
@endpush

@section('content')
<div class="container py-4" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card glass-card border-0 shadow-sm">
                <div class="card-header bg-white" style="border-radius:22px 22px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square"></i>
                        <h5 class="mb-0 fw-semibold">تعديل موظف</h5>
                        <a href="{{ route('inputs.staff') }}" class="ms-auto btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-right"></i> رجوع
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <div class="fw-bold mb-1">تحقق من الحقول التالية:</div>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('inputs.staff.update', $staff) }}" enctype="multipart/form-data" class="row g-3">
                        @csrf @method('PUT')

                        <div class="col-md-6">
                            <label class="form-label">اسم الموظف</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name',$staff->name) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">الرقم الوطني</label>
                            <input type="text" name="national_id" class="form-control" value="{{ old('national_id',$staff->national_id) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email',$staff->email) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">الهاتف</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone',$staff->phone) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">المسمّى الوظيفي</label>
                            <input type="text" name="job_title" class="form-control" value="{{ old('job_title',$staff->job_title) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">القسم</label>
                            <input type="text" name="department" class="form-control" value="{{ old('department',$staff->department) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">تاريخ التعيين</label>
                            <input type="date" name="hire_date" class="form-control" value="{{ old('hire_date', optional($staff->hire_date)->format('Y-m-d')) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">الراتب</label>
                            <input type="number" step="0.01" name="salary" class="form-control" value="{{ old('salary',$staff->salary) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">الحالة</label>
                            <select name="status" class="form-select" required>
                                @foreach(['نشط','موقوف','منتهٍ'] as $st)
                                    <option value="{{ $st }}" {{ old('status',$staff->status)===$st?'selected':'' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">العنوان</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address',$staff->address) }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label d-block">الصورة الحالية</label>
                            @if($staff->avatar_path)
                                <img src="{{ asset('storage/'.$staff->avatar_path) }}" class="avatar" alt="avatar">
                            @else
                                <span class="text-muted">لا توجد صورة.</span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">استبدال الصورة (اختياري)</label>
                            <input type="file" name="avatar" class="form-control" accept="image/*">
                            <small class="text-muted d-block mt-1">الحد الأقصى 5MB.</small>
                        </div>

                        <div class="col-12 d-flex gap-2">
                            <button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> حفظ التعديلات</button>
                            <a href="{{ route('inputs.staff') }}" class="btn btn-outline-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
