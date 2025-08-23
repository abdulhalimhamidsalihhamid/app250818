@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .glass-card{background:rgba(255,255,255,.35);border:1px solid rgba(255,255,255,.55);border-radius:22px;box-shadow:0 8px 24px rgba(0,0,0,.10);backdrop-filter:blur(10px)}
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
                        <h5 class="mb-0 fw-semibold">تعديل خبر</h5>
                        <a href="{{ route('inputs.news') }}" class="ms-auto btn btn-sm btn-outline-secondary">
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

                    <form method="POST" action="{{ route('inputs.news.update', $news) }}" enctype="multipart/form-data" class="row g-3">
                        @csrf @method('PUT')

                        <div class="col-md-6">
                            <label class="form-label">عنوان الخبر</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title',$news->title) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">تاريخ النشر</label>
                            <input type="date" name="published_at" class="form-control"
                                   value="{{ old('published_at', optional($news->published_at)->format('Y-m-d')) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">ملخّص قصير</label>
                            <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt',$news->excerpt) }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">نص الخبر</label>
                            <textarea name="body" class="form-control" rows="6" required>{{ old('body',$news->body) }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">تصنيف</label>
                            <select name="category" class="form-select">
                                <option value="">اختر تصنيفًا</option>
                                @foreach (['عام','طلاب','معلمون'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category',$news->category)===$cat ? 'selected':'' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label d-block">الوسائط الحالية</label>
                            @if($news->media_type === 'image' && $news->media_path)
                                <img src="{{ asset('storage/'.$news->media_path) }}" class="img-fluid rounded" style="max-height:220px;object-fit:cover">
                            @elseif($news->media_type === 'video' && $news->media_path)
                                <div class="ratio ratio-16x9">
                                    <video src="{{ asset('storage/'.$news->media_path) }}" controls playsinline></video>
                                </div>
                            @else
                                <div class="text-muted">لا توجد وسائط.</div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <label class="form-label d-block">نوع الوسائط (اختياري)</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="media_type" id="media_type_image"
                                       value="image" {{ old('media_type',$news->media_type)==='image'?'checked':'' }}>
                                <label class="form-check-label" for="media_type_image"><i class="bi bi-image"></i> صورة</label>
                            </div>
                            <div class="form-check form-check-inline me-3">
                                <input class="form-check-input" type="radio" name="media_type" id="media_type_video"
                                       value="video" {{ old('media_type',$news->media_type)==='video'?'checked':'' }}>
                                <label class="form-check-label" for="media_type_video"><i class="bi bi-camera-video"></i> فيديو قصير</label>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">استبدال الملف (اختياري)</label>
                            <input id="media_file" type="file" name="media_file" class="form-control" accept="image/*">
                            <small class="text-muted d-block mt-1" id="media_help">
                                الصور حتى 5MB أو الفيديو حتى 20MB. تركه فارغًا يعني الإبقاء على الملف الحالي.
                            </small>
                        </div>

                        <div class="col-12 d-flex gap-2">
                            <button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> حفظ التعديلات</button>
                            <a href="{{ route('inputs.news') }}" class="btn btn-outline-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
