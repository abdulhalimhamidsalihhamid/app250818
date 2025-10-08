@extends('layouts.app')

@push('styles')
  {{-- Bootstrap Icons + Leaflet (الخريطة) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

  <style>
    :root{
      --dark:#0f172a; --muted:#64748b; --page-bg:#f8fafc;
      --card-radius:18px; --border:1px solid rgba(15,23,42,.08);
      --shadow:0 10px 26px rgba(0,0,0,.08);
    }
    body{background:var(--page-bg); color:var(--dark); line-height:1.8;}
    [dir="rtl"] .text-start{ text-align:right !important; }

    /* HERO بسيط */
    .hero{
      border-radius:28px; overflow:hidden; color:#fff; position:relative;
      background: radial-gradient(1200px 420px at 90% -20%, #0b1220 0, #0f172a 45%, #1e293b 80%);
      min-height:360px; display:grid; place-items:center; text-align:center;
      box-shadow: var(--shadow);
    }
    .hero::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(14,21,37,.35),rgba(14,21,37,.1))}
    .hero .content{position:relative;padding:56px 20px}
    .hero h2{font-weight:800;font-size:clamp(26px,3.2vw,40px);margin-bottom:8px}
    .hero p{color:#cbd5e1;max-width:900px;margin:auto}

    /* البطاقات (الإحصاءات) */
    .stat-pro{background:#fff;border:var(--border);border-radius:16px;padding:16px;box-shadow:var(--shadow)}
    .stat-icon{width:52px;height:52px;border-radius:14px;display:grid;place-items:center;background:#f6f8ff;border:var(--border);font-size:22px}
    .muted{color:var(--muted)}

    /* عام للأقسام */
    .section{margin-top:28px}
    .section h5{font-weight:800;margin-bottom:10px}

    /* صندوق الوسائط للكاروسيل (صورة/فيديو/بديل نصي) */
    .media-wrap{position:relative;aspect-ratio:16/9;border-radius:14px;overflow:hidden;border:var(--border);background:linear-gradient(135deg,#eef2ff,#f8fafc)}
    .media-wrap img,.media-wrap video{width:100%;height:100%;object-fit:cover;display:block}
    .media-wrap .overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.55),rgba(0,0,0,0) 60%)}
    .media-wrap .badge{position:absolute;inset-inline-start:10px;top:10px;background:rgba(0,0,0,.6);color:#fff;border-radius:999px;padding:4px 10px;font-size:.8rem}
    .media-placeholder{width:100%;height:100%;display:grid;place-items:center;font-size:42px;color:#94a3b8}

    /* الكاروسيل (Bootstrap) */
    .carousel-box{background:#fff;border:var(--border);border-radius:16px;box-shadow:var(--shadow);padding:14px}
    .carousel-item{padding:2px} /* يمنع الالتصاق */
    .carousel-caption{right:0;left:0;bottom:12px;text-align:right;padding:0 14px 6px}
    .carousel-caption h6{color:#fff;font-weight:800;margin-bottom:2px;text-shadow:0 2px 10px rgba(0,0,0,.55)}
    .carousel-caption p{color:#e2e8f0;margin:0;text-shadow:0 2px 10px rgba(0,0,0,.55)}

    .carousel-indicators{margin-bottom:0.4rem}
    .carousel-indicators [data-bs-target]{width:8px;height:8px;border-radius:50%;background:#cbd5e1;opacity:1}
    .carousel-indicators .active{background:#111827}
    .carousel-control-prev,.carousel-control-next{width:auto}
    .carousel-control-prev-icon,.carousel-control-next-icon{
      background-color:#fff; border-radius:50%; padding:14px; box-shadow:var(--shadow); background-size:60% 60%; filter:invert(0);
    }

    /* الخريطة */
    #map{height:420px;border-radius:16px;border:var(--border);box-shadow:var(--shadow)}
  </style>
@endpush

@section('content')
<div class="container py-4" dir="rtl">

  {{-- تعريف المدرسة --}}

  <div style="width:100%; height:50vh; position:relative; background:url('images/12.jpg') center/cover no-repeat; display:flex; align-items:center; justify-content:center; text-align:center; color:#111;">

    <!-- طبقة الشفافية -->
    <div style="position:absolute; inset:0; background:rgba(255,255,255,0.6);"></div>

    <!-- المحتوى -->
    <div style="position:relative; z-index:2; padding:40px 20px;">
      <h2 style="font-size:42px; font-weight:bold; margin-bottom:15px;">مرحبًا بكم في بوابة الطلاب الثانوية</h2>
      <p style="font-size:20px; margin:0; opacity:0.9;">بوابة التميز الأكاديمي والأنشطة المدرسية وموارد الطلاب</p>
    </div>
  </div>

  {{-- إحصاءات --}}
  <div class="row g-3 mb-3">
    <div class="col-md-4">
      <div class="stat-pro d-flex align-items-center justify-content-between">
        <div>
          <div class="muted small mb-1">الطلاب</div>
          <div class="fw-bold fs-3"><span class="countup" data-value="{{ $stats['students'] }}">0</span></div>
        </div>
        <div class="stat-icon"><i class="bi bi-people"></i></div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-pro d-flex align-items-center justify-content-between">
        <div>
          <div class="muted small mb-1">المعلمون</div>
          <div class="fw-bold fs-3"><span class="countup" data-value="{{ $stats['teachers'] }}">0</span></div>
        </div>
        <div class="stat-icon"><i class="bi bi-mortarboard"></i></div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-pro d-flex align-items-center justify-content-between">
        <div>
          <div class="muted small mb-1">الموظفون</div>
          <div class="fw-bold fs-3"><span class="countup" data-value="{{ $stats['staff'] }}">0</span></div>
        </div>
        <div class="stat-icon"><i class="bi bi-person-vcard"></i></div>
      </div>
    </div>
  </div>

  {{-- آخر الأخبار (Bootstrap Carousel) --}}
  <section class="section">
    <h5><i class="bi bi-newspaper me-1"></i> آخر الأخبار</h5>

    @if($news->count())
      <div id="newsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4200">
        <div class="carousel-indicators">
          @foreach($news as $item)
            <button type="button" data-bs-target="#newsCarousel" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" aria-label="Slide {{ $loop->iteration }}"></button>
          @endforeach
        </div>

        <div class="carousel-inner">
          @foreach($news as $item)
            @php
              $isImg = $item->media_type==='image' && $item->media_path;
              $isVid = $item->media_type==='video' && $item->media_path;
            @endphp
            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
              <div class="carousel-box">
                <div class="media-wrap">
                  @if($isImg)
                    <img src="{{ asset('storage/'.$item->media_path) }}" alt="{{ $item->title }}">
                  @elseif($isVid)
                    <video src="{{ asset('storage/'.$item->media_path) }}" controls playsinline preload="metadata"></video>
                  @else
                    <div class="media-placeholder"><i class="bi bi-text-paragraph"></i></div>
                  @endif
                  <div class="overlay"></div>
                  @if($item->category)<span class="badge">{{ $item->category }}</span>@endif>

                  <div class="carousel-caption">
                    <h6 class="mb-1">{{ $item->title }}</h6>
                    <p class="small">
                      {{ \Illuminate\Support\Str::limit($item->excerpt ?: strip_tags($item->body), 150) }}
                      <span class="ms-2"><i class="bi bi-calendar-event"></i> {{ optional($item->published_at ?? $item->created_at)->format('Y-m-d') }}</span>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#newsCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">السابق</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#newsCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">التالي</span>
        </button>
      </div>
    @else
      <div class="alert alert-light border">لا توجد أخبار حالياً.</div>
    @endif
  </section>

  {{-- الأنشطة (Bootstrap Carousel) --}}
  <section class="section">
    <h5><i class="bi bi-calendar2-week me-1"></i> الأنشطة</h5>

    @if($activities->count())
      <div id="actCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4200">
        <div class="carousel-indicators">
          @foreach($activities as $a)
            <button type="button" data-bs-target="#actCarousel" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" aria-label="Slide {{ $loop->iteration }}"></button>
          @endforeach
        </div>

        <div class="carousel-inner">
          @foreach($activities as $a)
            @php
              $isImg = $a->media_type==='image' && $a->media_path;
              $isVid = $a->media_type==='video' && $a->media_path;
            @endphp
            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
              <div class="carousel-box">
                <div class="media-wrap">
                  @if($isImg)
                    <img src="{{ asset('storage/'.$a->media_path) }}" alt="{{ $a->title }}">
                  @elseif($isVid)
                    <video src="{{ asset('storage/'.$a->media_path) }}" controls playsinline preload="metadata"></video>
                  @else
                    <div class="media-placeholder"><i class="bi bi-activity"></i></div>
                  @endif
                  <div class="overlay"></div>
                  <span class="badge">{{ $a->category ?: 'نشاط' }}</span>

                  <div class="carousel-caption">
                    <h6 class="mb-1">{{ $a->title }}</h6>
                    <p class="small">
                      {{ \Illuminate\Support\Str::limit(strip_tags($a->description), 150) }}
                      <span class="ms-2"><i class="bi bi-calendar2"></i> {{ optional($a->date)->format('Y-m-d') }}</span>
                      @if($a->location)<span class="ms-2"><i class="bi bi-geo-alt"></i> {{ $a->location }}</span>@endif
                    </p>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#actCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">السابق</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#actCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">التالي</span>
        </button>
      </div>
    @else
      <div class="alert alert-light border">لا توجد أنشطة.</div>
    @endif
  </section>

  {{-- الإعلانات (Bootstrap Carousel) --}}
  <section class="section">
    <h5><i class="bi bi-megaphone me-1"></i> الإعلانات</h5>

    @if($announcements->count())
      <div id="annCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4200">
        <div class="carousel-indicators">
          @foreach($announcements as $ad)
            <button type="button" data-bs-target="#annCarousel" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" aria-label="Slide {{ $loop->iteration }}"></button>
          @endforeach
        </div>

        <div class="carousel-inner">
          @foreach($announcements as $ad)
            @php
              $isImg = $ad->media_type==='image' && $ad->media_path;
              $isVid = $ad->media_type==='video' && $ad->media_path;
            @endphp
            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
              <div class="carousel-box">
                <div class="media-wrap">
                  @if($isImg)
                    <img src="{{ asset('storage/'.$ad->media_path) }}" alt="{{ $ad->title }}">
                  @elseif($isVid)
                    <video src="{{ asset('storage/'.$ad->media_path) }}" controls playsinline preload="metadata"></video>
                  @else
                    <div class="media-placeholder"><i class="bi bi-megaphone"></i></div>
                  @endif
                  <div class="overlay"></div>
                  <span class="badge">لـ {{ $ad->audience ?: 'الكل' }}</span>

                  <div class="carousel-caption">
                    <h6 class="mb-1">{{ $ad->title }}</h6>
                    <p class="small">
                      {{ \Illuminate\Support\Str::limit(strip_tags($ad->body), 160) }}
                      <span class="ms-2"><i class="bi bi-clock"></i> {{ optional($ad->published_at ?? $ad->created_at)->format('Y-m-d') }}</span>
                      @if($ad->expires_at)<span class="ms-2 text-danger"><i class="bi bi-hourglass-split"></i> ينتهي {{ $ad->expires_at->format('Y-m-d') }}</span>@endif
                    </p>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#annCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">السابق</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#annCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">التالي</span>
        </button>
      </div>
    @else
      <div class="alert alert-light border">لا توجد إعلانات.</div>
    @endif
  </section>

  {{-- الخريطة في الأسفل --}}
  <section class="section">
    <h5 class="mb-2"><i class="bi bi-geo-alt me-1"></i> موقع المدرسة على الخريطة</h5>
    <div id="map"></div>
  </section>
</div>
@endsection

@push('scripts')
  {{-- CountUp + Leaflet (لا حاجة لـ Swiper) --}}
  <script src="https://cdn.jsdelivr.net/npm/countup.js@2.6.2/dist/countUp.umd.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

  <script>
    // عدّادات
    const obs = new IntersectionObserver((es)=>es.forEach(e=>{
      if(e.isIntersecting){
        const el=e.target, v=+el.dataset.value||0;
        const c=new window.CountUp.CountUp(el,v,{duration:1.2,separator:',',useEasing:true});
        if(!c.error) c.start();
        obs.unobserve(el);
      }
    }),{threshold:.6});
    document.querySelectorAll('.countup').forEach(el=>obs.observe(el));

    // الخريطة
(function(){
  // إحداثيات مدرسة قطرون الثانوية
  const lat = 24.8871306, lng = 14.5233853, zoom = 17;

  // إنشاء الخريطة داخل العنصر id=map
  const map = L.map('map', { scrollWheelZoom: false }).setView([lat, lng], zoom);

  // إضافة طبقة الخريطة (من OpenStreetMap)
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  // وضع علامة الموقع (Marker)
  L.marker([lat, lng]).addTo(map)
    .bindPopup('مدرسة قطرون الثانوية')
    .openPopup();
})();

  </script>
@endpush
