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

            <div class="card glass-card border-0 shadow-sm">
                <div class="card-header bg-white" style="border-radius:22px 22px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square"></i>
                        <h5 class="mb-0 fw-semibold">تعديل إعلان</h5>
                        <a href="{{ route('inputs.announcements') }}" class="ms-auto btn btn-sm btn-outline-secondary">
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

                    <form method="POST" action="{{ route('inputs.announcements.update', $announcement) }}" enctype="multipart/form-data" class="row g-3">
                        @csrf @method('PUT')

                        <div class="col-md-6">
                            <label class="form-label">عنوان الإعلان</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $announcement->title) }}" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">تاريخ النشر</label>
                            <input type="date" name="published_at" class="form-control"
                                   value="{{ old('published_at', optional($announcement->published_at)->format('Y-m-d')) }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">ينتهي في</label>
                            <input type="date" name="expires_at" class="form-control"
                                   value="{{ old('expires_at', optional($announcement->expires_at)->format('Y-m-d')) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">الجمهور المستهدف</label>
                            <select name="audience" class="form-select">
                                @foreach (['الكل','طلاب','معلمون','موظفون'] as $aud)
                                    <option value="{{ $aud }}" {{ old('audience', $announcement->audience) === $aud ? 'selected' : '' }}>{{ $aud }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">نص الإعلان</label>
                            <textarea name="body" class="form-control" rows="6" required>{{ old('body', $announcement->body) }}</textarea>
                        </div>

                        {{-- الوسائط الحالية --}}
                        <div class="col-md-6">
                            <label class="form-label d-block">الوسائط الحالية</label>
                            @if($announcement->media_type === 'image' && $announcement->media_path)
                                <img src="{{ asset('storage/'.$announcement->media_path) }}" class="img-fluid rounded" style="max-height:220px;object-fit:cover" alt="current media">
                            @elseif($announcement->media_type === 'video' && $announcement->media_path)
                                <div class="ratio ratio-16x9">
                                    <video src="{{ asset('storage/'.$announcement->media_path) }}" controls playsinline></video>
                                </div>
                            @else
                                <div class="text-muted">لا توجد وسائط.</div>
                            @endif
                        </div>

                        {{-- خيارات الوسائط الجديدة (اختياري) --}}
                        <div class="col-md-6">
                            <label class="form-label d-block">نوع الوسائط (اختياري)</label>
                            <div class="d-flex align-items-center gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="media_type" id="media_type_image"
                                           value="image" {{ old('media_type', $announcement->media_type) === 'image' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="media_type_image"><i class="bi bi-image"></i> صورة</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="media_type" id="media_type_video"
                                           value="video" {{ old('media_type', $announcement->media_type) === 'video' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="media_type_video"><i class="bi bi-camera-video"></i> فيديو قصير</label>
                                </div>
                            </div>

                            <label class="form-label mt-2">استبدال الملف (اختياري)</label>
                            <input id="media_file" type="file" name="media_file" class="form-control" accept="image/*">
                            <small class="text-muted d-block mt-1" id="media_help">
                                الصور حتى 5MB أو الفيديو حتى 20MB. تركه فارغًا يعني الإبقاء على الملف الحالي.
                            </small>
                        </div>

                        <div class="col-12 d-flex gap-2">
                            <button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> حفظ التعديلات</button>
                            <a href="{{ route('inputs.announcements') }}" class="btn btn-outline-secondary">إلغاء</a>
                        </div>
                    </form>
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

    function setAccept(){
        if(vidRadio && vidRadio.checked){
            fileIn.setAttribute('accept','video/mp4,video/webm,video/ogg');
        }else{
            fileIn.setAttribute('accept','image/*');
        }
    }
    if(imgRadio && vidRadio){
        imgRadio.addEventListener('change', setAccept);
        vidRadio.addEventListener('change', setAccept);
        setAccept();
    }
})();
</script>
@endpush
