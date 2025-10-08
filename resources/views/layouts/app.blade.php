<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root{
            --bg-url: url('/assets/bg-school.jpg'); /* ← غيّر المسار إن لزم */
            --overlay: rgba(255,255,255,.90);
            --nav-bg: rgba(255,255,255,.95);
            --nav-bd: #e9ecef;
        }

        /* تخطيط بوجود فوتر دائم أسفل الصفحة */
        html, body { height: 100%; }
        body { min-height: 100vh; }
        .app-shell { min-height: 100vh; display:flex; flex-direction:column; }
        main { flex: 1 0 auto; }
        footer { flex-shrink: 0; }

        /* خلفية عامة مع طبقة تفتيح */
        body.app-bg{
             background: url('{{ asset("images/bg-school.jpg") }}') center/cover fixed no-repeat;
        font-family: 'Nunito', sans-serif;
        }

        /* نافبار زجاجي */
        .navbar-glass{
            background: var(--nav-bg) !important;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            border-bottom: 1px solid var(--nav-bd);
        }
        .navbar-brand{ font-weight:700; letter-spacing:.25px }
        .nav-link{ display:inline-flex; align-items:center; gap:.4rem }

        /* منسدلة أنيقة */
        .dropdown-menu{
            border-radius: 14px;
            border: 1px solid #edf0f3;
            box-shadow: 0 12px 28px rgba(0,0,0,.08);
        }

        /* حاوية المحتوى */
        .page-container{ padding-block: 1.25rem; }
        /* اجعل النافبار أعلى من الكروت */
.navbar, .navbar-glass { position: relative; z-index: 1040; }
/* ارفع قائمة الدروبداون فوق كل شيء */
.dropdown-menu { z-index: 1050; }

    </style>

    @stack('styles')
</head>
<body class="app-bg">
<div id="app" class="app-shell">

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm navbar-glass">
        <div class="container">
            <a class="navbar-brand d-inline-flex align-items-center gap-2" href="{{ url('/') }}">
                <i class="bi bi-mortarboard-fill"></i>
                <span>ثنوية القطرون</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarMain" aria-controls="navbarMain"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <!-- الروابط الرئيسية (يمين في RTL) -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="bi bi-house-door-fill"></i> الرئيسية
                        </a>
                    </li>

                    <!-- قائمة المدخلات -->
                   @php $role = auth()->user()->role ?? null; @endphp

    <li class="nav-item dropdown">

        <a class="nav-link dropdown-toggle d-inline-flex align-items-center gap-2"
           href="#" id="menuInputs" role="button"
           data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
           <i class="bi bi-ui-checks-grid"></i> المدخلات
        </a>

        <ul class="dropdown-menu shadow dropdown-menu-end" aria-labelledby="menuInputs">
            @if ($role === 'admin' || $role === 'staff' )
            {{-- admin + staff --}}
            <li>
                <a class="dropdown-item d-inline-flex align-items-center gap-2"
                   href="{{ route('inputs.civil') }}">
                    <i class="bi bi-card-list"></i> إدخال البيانات تسجيل المدني
                </a>
            </li>
            <li>
                <a class="dropdown-item d-inline-flex align-items-center gap-2"
                   href="{{ route('inputs.timetables') }}">
                    <i class="bi bi-calendar3"></i> إدخال الجداول
                </a>
            </li>
            <li>
                <a class="dropdown-item d-inline-flex align-items-center gap-2"
                   href="{{ route('inputs.results') }}">
                    <i class="bi bi-graph-up"></i> إدخال نتيجة الطلاب
                </a>
            </li>

            <li><hr class="dropdown-divider"></li>

            <li>
                <a class="dropdown-item d-inline-flex align-items-center gap-2"
                   href="{{ route('inputs.news') }}">
                    <i class="bi bi-newspaper"></i> إدخال الأخبار
                </a>
            </li>
            <li>
                <a class="dropdown-item d-inline-flex align-items-center gap-2"
                   href="{{ route('inputs.announcements') }}">
                    <i class="bi bi-megaphone"></i> إدخال الإعلانات
                </a>
            </li>
            <li>
                <a class="dropdown-item d-inline-flex align-items-center gap-2"
                   href="{{ route('inputs.activities') }}">
                    <i class="bi bi-trophy"></i> إدخال النشاطات
                </a>
            </li>

            <li><hr class="dropdown-divider"></li>

            {{-- فقط admin --}}
            @if ($role === 'admin')
                <li>
                    <a class="dropdown-item d-inline-flex align-items-center gap-2"
                       href="{{ route('inputs.staff') }}">
                        <i class="bi bi-people-fill"></i> إضافة الموظفين
                    </a>
                </li>
            @endif

            {{-- admin + staff --}}
            <li>
                <a class="dropdown-item d-inline-flex align-items-center gap-2"
                   href="{{ route('inputs.attendance') }}">
                    <i class="bi bi-check2-circle"></i> إدخال الحضور
                </a>
            </li>
            <li>
                <a class="dropdown-item d-inline-flex align-items-center gap-2"
                   href="{{ route('inputs.teachers') }}">
                    <i class="bi bi-person-badge"></i> إدخال بيانات المعلمين
                </a>
            </li>
            @endif
        </ul>

    </li>

    {{-- روابط سريعة --}}
    <li class="nav-item">
        <a class="nav-link" href="{{ route('profile.edit') }}">
            <i class="bi bi-gear"></i> حسابي
        </a>
    </li>

    @if ($role === 'admin')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.index') }}">
                <i class="bi bi-people-gear"></i> المستخدمون
            </a>
        </li>
    @endif
                <!-- يمين الشريط: Auth -->
                <ul class="navbar-nav ms-auto">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link d-inline-flex align-items-center gap-2" href="{{ route('login') }}">
                                    <i class="bi bi-box-arrow-in-left"></i> {{ __('Login') }}
                                </a>
                            </li>
                        @endif
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link d-inline-flex align-items-center gap-2" href="{{ route('register') }}">
                                    <i class="bi bi-person-plus"></i> {{ __('Register') }}
                                </a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarUser" class="nav-link dropdown-toggle d-inline-flex align-items-center gap-2"
                               href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUser">
                                <hr class="dropdown-divider">
                                <a class="dropdown-item d-inline-flex align-items-center gap-2"
                                   href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    {{-- المحتوى --}}
    <main class="page-container">
        @yield('content')
    </main>

    {{-- الفوتر --}}
    <footer class="py-3 bg-white border-top">
        <div class="container d-flex flex-column flex-lg-row align-items-center justify-content-between gap-2">
            <div class="text-muted small">
                © {{ date('Y') }} {{ config('app.name', 'Laravel') }} — جميع الحقوق محفوظة
            </div>
            <ul class="nav">
                <li class="nav-item"><a class="nav-link px-2 text-muted" href="#"><i class="bi bi-info-circle"></i> من نحن</a></li>
                <li class="nav-item"><a class="nav-link px-2 text-muted" href="#"><i class="bi bi-shield-lock"></i> الخصوصية</a></li>
                <li class="nav-item"><a class="nav-link px-2 text-muted" href="#"><i class="bi bi-envelope"></i> تواصل</a></li>
            </ul>
            <div class="d-inline-flex gap-3">
                <a href="#" class="text-muted"><i class="bi bi-facebook fs-5"></i></a>
                <a href="#" class="text-muted"><i class="bi bi-twitter-x fs-5"></i></a>
                <a href="#" class="text-muted"><i class="bi bi-github fs-5"></i></a>
                <a href="#" class="text-muted"><i class="bi bi-youtube fs-5"></i></a>
            </div>
        </div>
    </footer>

</div>

<!-- JS: bundle (يتضمن Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
