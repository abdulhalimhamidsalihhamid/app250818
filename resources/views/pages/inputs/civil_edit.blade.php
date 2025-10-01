@extends('layouts.app')

@section('content')
<div class="container py-4" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card" style="background: rgba(255,255,255,.35); border: 1px solid rgba(255,255,255,.55); border-radius: 22px; box-shadow: 0 8px 24px rgba(0,0,0,.10); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);">
                <div class="card-header" style="background: rgba(255,255,255,.40); border-bottom: 1px solid rgba(255,255,255,.55);">
                    <h5 class="mb-0 text-center">✏️ تعديل بيانات الطالب</h5>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <div class="fw-bold mb-1">تحقق من الحقول التالية:</div>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('students.update', $student) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">اسم الطالب</label>
                                <input type="text" class="form-control" name="student_name" value="{{ old('student_name', $student->student_name) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email', $student->email) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">تاريخ الميلاد</label>
                                <input type="date" class="form-control" name="dob" value="{{ old('dob', optional($student->dob)->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الرقم الوطني</label>
                                <input type="text" class="form-control" name="national_id" value="{{ old('national_id', $student->national_id) }}">
                            </div>
<div class="col-md-6">
    <label for="student_number" class="form-label">الرقم الدراسي <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('student_number') is-invalid @enderror"
           id="student_number" name="student_number" value="{{ old('student_number', $student->student_number) }}" placeholder="مثال: 2025001">
    @error('student_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
                            <div class="col-md-6">
                                <label class="form-label">رقم الهاتف</label>
                                <input type="text" class="form-control" name="phone" value="{{ old('phone', $student->phone) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الجنس</label>
                                <select name="gender" class="form-select">
                                    <option value=""     {{ old('gender',$student->gender)==='' ? 'selected':'' }}>—</option>
                                    <option value="male" {{ old('gender',$student->gender)==='male' ? 'selected':'' }}>ذكر</option>
                                    <option value="female" {{ old('gender',$student->gender)==='female' ? 'selected':'' }}>أنثى</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">القسم الدراسي</label>
                                <select name="department" class="form-select">
                                    @php $dep = old('department',$student->department); @endphp
                                    <option value="">—</option>
                                    <option value="science"    {{ $dep==='science' ? 'selected':'' }}>العلوم</option>
                                    <option value="literature" {{ $dep==='literature' ? 'selected':'' }}>الأدبي</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الصف / الشعبة</label>
                                <input type="text" class="form-control" name="class_name" value="{{ old('class_name', $student->class_name) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">تاريخ القيد</label>
                                <input type="date" class="form-control" name="enrollment_date" value="{{ old('enrollment_date', optional($student->enrollment_date)->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">فصيلة الدم</label>
                                @php $bt = old('blood_type', $student->blood_type); @endphp
                                <select name="blood_type" class="form-select">
                                    <option value="">—</option>
                                    @foreach (['O+','O-','A+','A-','B+','B-','AB+','AB-'] as $x)
                                        <option value="{{ $x }}" {{ $bt===$x ? 'selected':'' }}>{{ $x }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">العنوان</label>
                                <textarea class="form-control" name="address" rows="2">{{ old('address', $student->address) }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">اسم ولي الأمر</label>
                                <input type="text" class="form-control" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">هاتف ولي الأمر</label>
                                <input type="text" class="form-control" name="guardian_phone" value="{{ old('guardian_phone', $student->guardian_phone) }}">
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-primary px-4">حفظ التعديلات</button>
                            <a href="{{ route('students.create') }}" class="btn btn-outline-secondary">عودة للقائمة</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
