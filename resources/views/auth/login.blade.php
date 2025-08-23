@extends('layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
@endpush

@section('content')
<div class="container py-4" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex align-items-center gap-2">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span class="fw-semibold">تسجيل الدخول</span>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- بريـد إلكتروني أو رقم وطني --}}
                        <div class="mb-3">
                            <label for="login" class="form-label">البريد الإلكتروني أو الرقم الوطني</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                                <input id="login" type="text"
                                       class="form-control @error('login') is-invalid @enderror @error('email') is-invalid @enderror"
                                       name="login" value="{{ old('login', old('email')) }}" required autofocus
                                       placeholder="you@example.com أو 1234567890123">
                            </div>
                            {{-- نعرض أي رسالة خطأ على login أو email (لبعض الحِزم القديمة) --}}
                            @error('login')   <div class="invalid-feedback d-block"><strong>{{ $message }}</strong></div> @enderror
                            @error('email')   <div class="invalid-feedback d-block"><strong>{{ $message }}</strong></div> @enderror
                        </div>

                        {{-- كلمة المرور --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       name="password" required autocomplete="current-password" placeholder="••••••••">
                            </div>
                            @error('password') <div class="invalid-feedback d-block"><strong>{{ $message }}</strong></div> @enderror
                        </div>

                        {{-- تذكرني + روابط مساعدة --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    تذكّرني
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                                <a class="text-decoration-none small" href="{{ route('password.request') }}">
                                    نسيت كلمة المرور؟
                                </a>
                            @endif
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2-circle me-1"></i> دخول
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- لديك حساب جديد؟ --}}
            <div class="text-center mt-3">
                <small class="text-muted">
                    لا تملك حسابًا؟ <a href="{{ route('register') }}" class="text-decoration-none">أنشئ حسابًا</a>
                </small>
            </div>

        </div>
    </div>
</div>
@endsection
