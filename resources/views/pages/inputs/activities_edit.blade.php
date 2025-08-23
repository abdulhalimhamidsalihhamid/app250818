@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>.glass-card{background:rgba(255,255,255,.35);border:1px solid rgba(255,255,255,.55);border-radius:22px;box-shadow:0 8px 24px rgba(0,0,0,.10);backdrop-filter:blur(10px)}</style>
@endpush

@section('content')
<div class="container py-4" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card glass-card border-0 shadow-sm">
                <div class="card-header bg-white" style="border-radius:22px 22px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square"></i>
                        <h5 class="mb-0 fw-semibold">تعديل نشاط</h5>
                        <a href="{{ route('inputs.activities') }}" class="ms-auto btn btn-sm btn-outline-secondary">
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

                    <form method="POST" action="{{ route('inputs.activities.update', $activity) }}" enctype="multipart/form-data" class="row g-3">
                        @csrf @method('PUT')

                        <div class="col-md-6">
                            <label class="form-label">عنوان النشاط</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title',$activity->title) }}" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">تاريخ النشاط</label>
                            <input type="date" name="date" class="form-control" value="{{ old('date', optional($activity->date)->format('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">الموقع</label>
                            <input type="text" name="location" class="form-control" value="{{ old('location',$activity->location) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">التصنيف</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category',$activity->category) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">وصف النشاط</label>
                            <textarea name="description" class="form-control" rows="6" required>{{ old('description',$activity->description) }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label d-block">الوسائط الحالية</label>
                            @if($activity->media_type === 'image' && $activity->media_path)
                                <img src="{{ asset('storage/'.$activity->media_path) }}" class="img-fluid rounded" style="max-height:220px;object-fit:cover">
                            @elseif($activity->media_type === 'video' && $activity->media_path)
                                <div class="ratio ratio-16x9">
                                    <video src="{{ asset('storage/'.$activity->media_path) }}" controls playsinline></video>
                                </div>
                            @else
                                <div class="text-muted">لا توجد وسائط.</div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label d-block">نوع الوسائط (اختياري)</label>
                            <div class="d-flex align-items-center gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="media_type" id="media_type_image"
                                           value="image" {{ old('media_type',$activity->media_type)==='image'?'checked':'' }}>
                                    <label class="form-check-label" for="media_type_image"><i class="bi bi-image"></i> صورة</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="media_type" id="media_type_video"
                                           value="video" {{ old('media_type',$activity->media_type)==='video'?'checked':'' }}>
                                    <label class="form-check-label" for="media_type_video"><i class="bi bi-camera-video"></i> فيديو قصير</label>
                                </div>
                            </div>

                            <label class="form-label mt-2">استبدال الملف (اختياري)</label>
                            <input id="media_file" type="file" name="media_file" class="form-control" accept="image/*">
                            <small class="text-muted d-block mt-1">الصور حتى 5MB — الفيديو حتى 20MB.</small>
                        </div>

                        <div class="col-12 d-flex gap-2">
                            <button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> حفظ التعديلات</button>
                            <a href="{{ route('inputs.activities') }}" class="btn btn-outline-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
