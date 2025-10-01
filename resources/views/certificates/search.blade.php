{{-- resources/views/certificates/search.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card glass-card border-0 shadow-sm">
                <div class="card-header bg-white rounded-top-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-search"></i> بحث عن شهادة</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('certificates.showByCode') }}" class="d-flex gap-2">
                        <input type="text" name="code" class="form-control text-center"
                               placeholder="ادخل رقم الشهادة المكون من 6 أرقام" required>
                        <button class="btn btn-primary"><i class="bi bi-arrow-right-circle"></i> عرض</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
