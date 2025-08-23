@extends('layouts.app')

@section('content')
<div class="container py-4" dir="rtl">
    <style>
        :root { --primary:#0d6efd; --card-border:#e9ecef; --soft:#f8f9fa; }
        .dash-title{font-weight:600; letter-spacing:.2px}
        .dash-grid{display:grid; grid-template-columns:repeat(auto-fill,minmax(240px,1fr)); gap:1rem}
        .dash-link{display:block; text-decoration:none; color:inherit; outline:none}
        .dash-card{
            background:#fff; border:1px solid var(--card-border); border-radius:18px; padding:18px 20px;
            box-shadow:0 6px 14px rgba(0,0,0,.04);
            transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease, background .25s ease;
            opacity:0; transform:translateY(16px);
            animation:fadeUp .6s ease forwards;
        }
        .dash-card:hover{transform:translateY(-4px); box-shadow:0 12px 24px rgba(0,0,0,.08)}
        .dash-card:focus-within{box-shadow:0 0 0 .25rem rgba(13,110,253,.2)}
        .icon{
            width:46px; height:46px; border-radius:12px; background:var(--soft);
            display:flex; align-items:center; justify-content:center; font-size:20px; margin-bottom:10px;
        }
        .dash-card .title{font-weight:600}
        .dash-card .desc{color:#6c757d; font-size:.92rem; margin-top:.25rem}

        @keyframes fadeUp{to{opacity:1; transform:translateY(0)}}
        /* تتابع الانيميشن */
        .dash-card:nth-child(1){animation-delay:.05s}
        .dash-card:nth-child(2){animation-delay:.10s}
        .dash-card:nth-child(3){animation-delay:.15s}
        .dash-card:nth-child(4){animation-delay:.20s}
        .dash-card:nth-child(5){animation-delay:.25s}
        .dash-card:nth-child(6){animation-delay:.30s}
        .dash-card:nth-child(7){animation-delay:.35s}
        .dash-card:nth-child(8){animation-delay:.40s}
        .dash-card:nth-child(9){animation-delay:.45s}
    </style>

    <div class="row justify-content-center ">
        <div class="col-lg-11">
            <div class="card border-1 rounded-5 shadow">
                <div class="card-header bg-white ">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 dash-title">لوحة التحكم — إدارة المدرسة</h5>
                        <h5 class="text-muted small">مرحبًا {{ auth()->user()->name ?? 'بك' }}</h5>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success text-center" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="dash-grid mt-2">
                        <a href="{{ route('inputs.civil') }}" class="dash-link">
                            <div class="dash-card">
                                <div class="icon">🧾</div>
                                <div class="title">إدخال البيانات تسجيل المدني</div>
                                <div class="desc">تسجيل بيانات الطلاب الأساسية.</div>
                            </div>
                        </a>

                        <a href="{{ route('inputs.timetables') }}" class="dash-link">
                            <div class="dash-card">
                                <div class="icon">📅</div>
                                <div class="title">إدخال الجداول</div>
                                <div class="desc">جداول الحصص والفصول.</div>
                            </div>
                        </a>

                        <a href="{{ route('inputs.results') }}" class="dash-link">
                            <div class="dash-card">
                                <div class="icon">📊</div>
                                <div class="title">إدخال نتيجة الطلاب</div>
                                <div class="desc">درجات الفصول والامتحانات.</div>
                            </div>
                        </a>

{{-- إدخال النتائج (بحسب المعلّم/المقرر) --}}
<a href="{{ route('inputs.teacher_results') }}" class="dash-link" aria-label="إدخال نتائج الطلاب">
    <div class="dash-card">
        <div class="icon"><i class="bi bi-journal-check"></i></div>
        <div class="title">إدخال نتائج الطلاب</div>
        <div class="desc">إدخال/تعديل الدرجات بحسب المعلّم والمقرّر.</div>
    </div>
</a>

{{-- عرض النتائج --}}
<a href="{{ route('results.student') }}" class="dash-link" aria-label="عرض نتائج الطلاب">
    <div class="dash-card">
        <div class="icon"><i class="bi bi-clipboard2-data"></i></div>
        <div class="title">عرض نتائج الطلاب</div>
        <div class="desc">استعراض نتائج الطالب حسب الفصل والسنة.</div>
    </div>
</a>

                        <a href="{{ route('inputs.news') }}" class="dash-link">
                            <div class="dash-card">
                                <div class="icon">📰</div>
                                <div class="title">إدخال الأخبار</div>
                                <div class="desc">أحدث أخبار المدرسة.</div>
                            </div>
                        </a>

                        <a href="{{ route('inputs.announcements') }}" class="dash-link">
                            <div class="dash-card">
                                <div class="icon">📣</div>
                                <div class="title">إدخال الإعلانات</div>
                                <div class="desc">تنبيهات مهمة للطلاب والموظفين.</div>
                            </div>
                        </a>

                        <a href="{{ route('inputs.activities') }}" class="dash-link">
                            <div class="dash-card">
                                <div class="icon">🎯</div>
                                <div class="title">إدخال النشاطات</div>
                                <div class="desc">أنشطة ومسابقات المدرسة.</div>
                            </div>
                        </a>

                        <a href="{{ route('inputs.staff') }}" class="dash-link">
                            <div class="dash-card">
                                <div class="icon">👥</div>
                                <div class="title">إضافة الموظفين</div>
                                <div class="desc">تسجيل بيانات العاملين.</div>
                            </div>
                        </a>

                        <a href="{{ route('inputs.attendance') }}" class="dash-link">
                            <div class="dash-card">
                                <div class="icon">✅</div>
                                <div class="title">إدخال الحضور</div>
                                <div class="desc">تتبّع حضور الطلاب والموظفين.</div>
                            </div>
                        </a>

                        <a href="{{ route('inputs.teachers') }}" class="dash-link">
                            <div class="dash-card">
                                <div class="icon">👨‍🏫</div>
                                <div class="title">إدخال بيانات المعلمين</div>
                                <div class="desc">الملفات الأكاديمية للمعلمين.</div>
                            </div>
                        </a>

                        <a href="{{ route('profile.edit') }}" class="dash-link">
                            <div class="dash-card"><div class="icon">⚙️</div>
                                <div class="title">تعديل الحساب</div>
                                <div class="desc">الاسم، البريد، كلمة المرور.</div>
                            </div>
                        </a>

                        <a href="{{ route('admin.users.index') }}" class="dash-link">
                            <div class="dash-card"><div class="icon">👥</div>
                                <div class="title">المستخدمون</div>
                                <div class="desc">إضافة/تعديل/حذف مستخدمين.</div>
                            </div>
                        </a>
                    </div>
                     {{-- /dash-grid --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
