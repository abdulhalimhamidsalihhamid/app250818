@extends('layouts.app')

@section('content')
<div class="container py-4" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">تعديل الحساب</div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success text-center">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" class="row g-3">
                        @csrf @method('PATCH')

                        <div class="col-md-6">
                            <label class="form-label">الاسم</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">الرقم الوطني (اختياري)</label>
                            <input type="text" name="national_id" class="form-control"
                                   value="{{ old('national_id', $user->national_id) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">كلمة المرور الجديدة (اختياري)</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••">
                            <small class="text-muted">اتركها فارغة إن لم ترغب بتغييرها.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••">
                        </div>

                        <div class="col-12 d-flex gap-2">
                            <button class="btn btn-primary">حفظ</button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <form method="POST" action="{{ route('profile.destroy') }}"
                          onsubmit="return confirm('هل أنت متأكد من حذف الحساب نهائياً؟');">
                        @csrf @method('DELETE')
                        <div class="mb-2">لحذف الحساب، أدخل كلمة المرور الحالية:</div>
                        <div class="d-flex gap-2">
                            <input type="password" name="password" class="form-control" placeholder="كلمة المرور" required>
                            <button class="btn btn-outline-danger">حذف الحساب</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
