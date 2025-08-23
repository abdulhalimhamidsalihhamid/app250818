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
        <div class="col-lg-10">

            {{-- بطاقة إضافة خبر --}}
            <div class="card glass-card border-0 shadow-sm">
                <div class="card-header bg-white" style="border-radius: 22px 22px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-megaphone"></i>
                        <h5 class="mb-0 fw-semibold">إضافة خبر</h5>
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

                    <form method="POST" action="{{ route('inputs.news.store') }}" enctype="multipart/form-data" class="row g-3">
                        @csrf

                        <div class="col-md-6">
                            <label class="form-label">عنوان الخبر</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-type"></i></span>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="اكتب عنوانًا واضحًا" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">تاريخ النشر</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                <input type="date" name="published_at" class="form-control" value="{{ old('published_at', date('Y-m-d')) }}">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">ملخّص قصير</label>
                            <textarea name="excerpt" class="form-control" rows="2" placeholder="سطران يوضحان مضمون الخبر">{{ old('excerpt') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">نص الخبر</label>
                            <textarea name="body" class="form-control" rows="6" placeholder="تفاصيل الخبر كاملة" required>{{ old('body') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">تصنيف</label>
                            <select name="category" class="form-select">
                                <option value="">اختر تصنيفًا</option>
                                @foreach (['عام','طلاب','معلمون'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category')===$cat ? 'selected':'' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- الوسائط (اختياري) --}}
                        <div class="col-md-6">
                            <label class="form-label d-block">الوسائط (اختياري)</label>
                            <div class="d-flex align-items-center gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="media_type" id="media_type_image"
                                           value="image" {{ old('media_type','image')==='image'?'checked':'' }}>
                                    <label class="form-check-label" for="media_type_image"><i class="bi bi-image"></i> صورة</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="media_type" id="media_type_video"
                                           value="video" {{ old('media_type')==='video'?'checked':'' }}>
                                    <label class="form-check-label" for="media_type_video"><i class="bi bi-camera-video"></i> فيديو قصير</label>
                                </div>
                                <div class="text-muted small">— يمكنك تركها فارغة</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">ملف الوسائط</label>
                            <input id="media_file" type="file" name="media_file" class="form-control" accept="image/*">
                            <small class="text-muted d-block mt-1" id="media_help">
                                الصور: JPG/PNG/WEBP/GIF حتى 5MB — الفيديو: MP4/WEBM/OGG حتى 20MB.
                            </small>
                        </div>

                        {{-- المعاينة --}}
                        <div class="col-12">
                            <div class="border rounded p-3 bg-light">
                                <div class="fw-semibold mb-2"><i class="bi bi-eye"></i> معاينة</div>
                                <img id="previewImage" class="img-fluid d-none" style="max-height:320px;border-radius:12px;">
                                <video id="previewVideo" class="w-100 d-none" style="max-height:360px;border-radius:12px;" controls playsinline></video>
                            </div>
                        </div>

                        <div class="col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2-circle me-1"></i> حفظ
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">مسح</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- قائمة الأخبار (أسفل الصفحة) --}}
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white d-flex align-items-center gap-2">
                    <i class="bi bi-list-ul"></i>
                    <span class="fw-semibold">آخر الأخبار</span>
                </div>

                <div class="card-body">
                    @if(isset($news) && $news->count())
                        <div class="row g-3">
                            @foreach($news as $item)
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">

                                        {{-- الوسائط --}}
                                        @if($item->media_type === 'image' && $item->media_path)
                                            <img src="{{ asset('storage/'.$item->media_path) }}"
                                                 class="card-img-top" style="max-height:220px;object-fit:cover" alt="news image">
                                        @elseif($item->media_type === 'video' && $item->media_path)
                                            <div class="ratio ratio-16x9">
                                                <video src="{{ asset('storage/'.$item->media_path) }}" controls playsinline></video>
                                            </div>
                                        @endif

                                        <div class="card-body d-flex flex-column">
                                            <h6 class="card-title mb-1">{{ $item->title }}</h6>
                                            <small class="text-muted d-block mb-2">
                                                {{ optional($item->published_at)->format('Y-m-d') }}
                                                — {{ $item->category ?? 'غير مصنّف' }}
                                            </small>

                                            @if($item->excerpt)
                                                <p class="text-muted small mb-3">{{ $item->excerpt }}</p>
                                            @endif

                                            <div class="mt-auto d-flex gap-2">
                                                <a href="{{ route('inputs.news.edit', $item) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil-square"></i> تعديل
                                                </a>
                                                <form method="POST" action="{{ route('inputs.news.destroy', $item) }}"
                                                      onsubmit="return confirm('تأكيد حذف الخبر؟');">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i> حذف
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            {{ $news->links() }}
                        </div>
                    @else
                        <div class="alert alert-info mb-0">لا توجد أخبار بعد.</div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    const imgRadio = document.getElementById('media_type_image');
    const vidRadio = document.getElementById('media_type_video');
    const fileIn   = document.getElementById('media_file');
    const imgPrev  = document.getElementById('previewImage');
    const vidPrev  = document.getElementById('previewVideo');

    function setAccept(){
        if(vidRadio && vidRadio.checked){
            fileIn.setAttribute('accept','video/mp4,video/webm,video/ogg');
        }else{
            fileIn.setAttribute('accept','image/*');
        }
        clearPreview();
    }
    function clearPreview(){
        imgPrev.src = ''; vidPrev.src='';
        imgPrev.classList.add('d-none');
        vidPrev.classList.add('d-none');
    }
    function preview(){
        clearPreview();
        const f = fileIn.files && fileIn.files[0];
        if(!f) return;
        const url = URL.createObjectURL(f);
        if(vidRadio && vidRadio.checked){
            vidPrev.src = url; vidPrev.classList.remove('d-none');
        }else{
            imgPrev.src = url; imgPrev.classList.remove('d-none');
        }
    }

    if(imgRadio && vidRadio){
        imgRadio.addEventListener('change', setAccept);
        vidRadio.addEventListener('change', setAccept);
    }
    if(fileIn){ fileIn.addEventListener('change', preview); }
    setAccept();
})();
</script>
@endpush
