@extends('layouts.app')

@section('content')
<div class="container py-4 bg-light-subtle" dir="rtl">
    <h3 class="mb-4 text-center">📅 إدخال الجدول الدراسي</h3>

    {{-- فورم إدخال --}}
    <form action="{{ route('inputs.timetables.store') }}" method="POST" class="mb-5">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <label>اليوم</label>
                <select name="day" class="form-control">
                    <option>الأحد</option>
                    <option>الإثنين</option>
                    <option>الثلاثاء</option>
                    <option>الأربعاء</option>
                    <option>الخميس</option>
                </select>
            </div>
            <div class="col-md-6">
                <label>التخصص</label>
                <select name="specialization" class="form-control">
                    <option value="general">عام</option>
                    <option value="science"  >علمي</option>
                    <option value="literature">أدبي</option>
                </select>
            </div>
            <div class="col-md-4">
    <label class="form-label">الصف الدراسي</label>
    <select name="grade" class="form-select @error('grade') is-invalid @enderror">
        @php $gOld = old('grade'); @endphp
        @foreach (['الأولى','الثانية','الثالثة'] as $g)
            <option value="{{ $g }}" {{ $gOld===$g ? 'selected':'' }}>{{ $g }}</option>
        @endforeach
    </select>
    @error('grade') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

        </div>

        {{-- 7 حصص --}}
        <div class="row">
            @for($i=1; $i<=7; $i++)
                <div class="col-md-6 mb-3">
                    <label>الحصة {{ $i }}</label>
                    <input type="text" name="period{{ $i }}" class="form-control" placeholder="أدخل اسم المادة">
                </div>
            @endfor
        </div>

        <button class="btn btn-success">💾 حفظ الجدول</button>
    </form>

    {{-- عرض الجداول --}}
    <table class="table table-bordered text-center">
        <thead class="table-light">
            <tr>
                <th>اليوم</th>
                <th>التخصص</th>
                <th>الفصل</th>
                @for($i=1; $i<=7; $i++)
                    <th>حصة {{ $i }}</th>
                @endfor
            </tr>
        </thead>
<tbody>
@forelse($timetables as $t)
    <tr>
        <td>{{ $t->day }}</td>
        <td>

               @if($t->specialization == "literature")
        أدبي
    @elseif($t->specialization == "general")
        عام
    @elseif($t->specialization == "science")
        علمي
    @else
        —
    @endif

        </td>
        <td>{{ $t->grade }}</td>
        <td>{{ $t->period1 }}</td>
        <td>{{ $t->period2 }}</td>
        <td>{{ $t->period3 }}</td>
        <td>{{ $t->period4 }}</td>
        <td>{{ $t->period5 }}</td>
        <td>{{ $t->period6 }}</td>
        <td>{{ $t->period7 }}</td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="text-center text-muted">لا توجد جداول بعد</td>
    </tr>
@endforelse
</tbody>

    </table>
</div>
@endsection
