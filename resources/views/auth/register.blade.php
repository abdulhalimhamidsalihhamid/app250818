@extends('layouts.app')

@push('styles')
    {{-- Bootstrap Icons (إن لم تكن موجودة في الـ layout) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
@endpush

@section('content')
<div class="container py-4" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex align-items-center gap-2">
                    <i class="bi bi-person-plus-fill"></i>
                    <span class="fw-semibold">التسجيل</span>
                </div>

                <div class="card-body">
                    {{-- رسائل نجاح/أخطاء عامة (اختياري) --}}
                    @if (session('status'))
                        <div class="alert alert-success text-center">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- الاسم --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">الاسم الكامل</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input id="name" type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                                       placeholder="اكتب اسمك الكامل">
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>

                        {{-- الرقم الوطني --}}
                        <div class="mb-3">
                            <label for="national_id" class="form-label">الرقم الوطني</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-fingerprint"></i></span>
                                <input id="national_id" type="text"
                                       class="form-control @error('national_id') is-invalid @enderror"
                                       name="national_id" value="{{ old('national_id') }}" required
                                       placeholder="مثال: 1234567890123">
                            </div>
                            @error('national_id')
                                <div class="invalid-feedback d-block"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>

                        {{-- البريد الإلكتروني --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input id="email" type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" required autocomplete="email"
                                       placeholder="you@example.com">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>

                        {{-- كلمة المرور --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       name="password" required autocomplete="new-password"
                                       placeholder="••••••••">
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>

                        {{-- تأكيد كلمة المرور --}}
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label">تأكيد كلمة المرور</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" required autocomplete="new-password"
                                       placeholder="أعد إدخال كلمة المرور">
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2-circle me-1"></i>
                                تسجيل جديد
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- تلميح اختياري: لديك حساب مسبقًا؟ --}}
            <div class="text-center mt-3">
                <small class="text-muted">
                    لديك حساب بالفعل؟
                    <a href="{{ route('login') }}" class="text-decoration-none">سجّل الدخول</a>
                </small>
            </div>

        </div>
    </div>
</div>
@endsection
