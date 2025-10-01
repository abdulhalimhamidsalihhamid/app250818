@extends('layouts.app')

@section('content')
<div class="container py-4" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card" style="
                background: rgba(255,255,255,.35);
                border: 1px solid rgba(255,255,255,.55);
                border-radius: 22px;
                box-shadow: 0 8px 24px rgba(0,0,0,.10);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            ">
                <div class="card-header" style="background: rgba(255,255,255,.40); border-bottom: 1px solid rgba(255,255,255,.55);">
                    <h5 class="mb-0 text-center">📋 إدخال بيانات الطالب</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('inputs.civil.store') }}" method="POST">
    @csrf

    {{-- عرض الأخطاء العامة --}}
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

    <h6 class="text-muted fw-bold border-bottom pb-2 mb-3">البيانات الأساسية</h6>
    <div class="row g-3">
        <div class="col-md-6">
            <label for="student_name" class="form-label">اسم الطالب <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('student_name') is-invalid @enderror"
                   id="student_name" name="student_name" value="{{ old('student_name') }}" placeholder="أدخل اسم الطالب">
            @error('student_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}" placeholder="student@example.com">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label for="dob" class="form-label">تاريخ الميلاد</label>
            <input type="date" class="form-control @error('dob') is-invalid @enderror"
                   id="dob" name="dob" value="{{ old('dob') }}">
            @error('dob') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label for="national_id" class="form-label">الرقم الوطني</label>
            <input type="text" class="form-control @error('national_id') is-invalid @enderror"
                   id="national_id" name="national_id" value="{{ old('national_id') }}" placeholder="مثال: 1234567890123">
            @error('national_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
<div class="col-md-6">
    <label for="student_number" class="form-label">الرقم الدراسي <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('student_number') is-invalid @enderror"
           id="student_number" name="student_number" value="{{ old('student_number') }}" placeholder="مثال: 2025001">
    @error('student_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
        <div class="col-md-6">
            <label for="phone" class="form-label">رقم الهاتف</label>
            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                   id="phone" name="phone" value="{{ old('phone') }}" placeholder="09XXXXXXXX">
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label for="gender" class="form-label">النوع</label>
            <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror">
                <option disabled {{ old('gender') ? '' : 'selected' }}>اختر النوع</option>
                <option value="male"   {{ old('gender')==='male' ? 'selected' : '' }}>ذكر</option>
                <option value="female" {{ old('gender')==='female' ? 'selected' : '' }}>أنثى</option>
            </select>
            @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label for="department" class="form-label">القسم الدراسي</label>
            <select id="department" name="department" class="form-select @error('department') is-invalid @enderror">
                <option disabled {{ old('department') ? '' : 'selected' }}>اختر القسم</option>
                <option value="علمي"    {{ old('department')==='علمي' ? 'selected':'' }}>علمي</option>
                <option value="أدبي" {{ old('department')==='أدبي' ? 'selected':'' }}>الأدبي</option>
            </select>
            @error('department') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label for="class_name" class="form-label">الصف / الشعبة</label>
            <input type="text" class="form-control @error('class_name') is-invalid @enderror"
                   id="class_name" name="class_name" value="{{ old('class_name') }}" placeholder="مثال: الأول ث/أ">
            @error('class_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label for="enrollment_date" class="form-label">تاريخ القيد</label>
            <input type="date" class="form-control @error('enrollment_date') is-invalid @enderror"
                   id="enrollment_date" name="enrollment_date" value="{{ old('enrollment_date') }}">
            @error('enrollment_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label for="blood_type" class="form-label">فصيلة الدم</label>
            <select id="blood_type" name="blood_type" class="form-select @error('blood_type') is-invalid @enderror">
                <option disabled {{ old('blood_type') ? '' : 'selected' }}>اختر الفصيلة</option>
                @foreach (['O+','O-','A+','A-','B+','B-','AB+','AB-'] as $bt)
                    <option value="{{ $bt }}" {{ old('blood_type')===$bt ? 'selected':'' }}>{{ $bt }}</option>
                @endforeach
            </select>
            @error('blood_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <label for="address" class="form-label">العنوان</label>
            <textarea class="form-control @error('address') is-invalid @enderror"
                      id="address" name="address" rows="2" placeholder="المدينة، الحي، الشارع...">{{ old('address') }}</textarea>
            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <h6 class="text-muted fw-bold border-bottom pb-2 mt-4 mb-3">بيانات ولي الأمر</h6>
    <div class="row g-3">
        <div class="col-md-6">
            <label for="guardian_name" class="form-label">اسم ولي الأمر</label>
            <input type="text" class="form-control @error('guardian_name') is-invalid @enderror"
                   id="guardian_name" name="guardian_name" value="{{ old('guardian_name') }}" placeholder="اسم ولي الأمر">
            @error('guardian_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label for="guardian_phone" class="form-label">هاتف ولي الأمر</label>
            <input type="tel" class="form-control @error('guardian_phone') is-invalid @enderror"
                   id="guardian_phone" name="guardian_phone" value="{{ old('guardian_phone') }}" placeholder="09XXXXXXXX">
            @error('guardian_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary px-4">💾 حفظ البيانات</button>
        <button type="reset" class="btn btn-outline-secondary">↺ إلغاء</button>
    </div>
</form>

                </div>

            </div>
        </div>
    </div>

     {{-- جدول عرض الطلاب --}}
 {{-- جدول عرض الطلاب أسفل الفورم --}}
            <div class="card mt-4" style="background: rgba(255,255,255,.35); border: 1px solid rgba(255,255,255,.55); border-radius: 22px; box-shadow: 0 8px 24px rgba(0,0,0,.10); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);">
                <div class="card-header" style="background: rgba(255,255,255,.40); border-bottom: 1px solid rgba(255,255,255,.55);">
                    <h6 class="mb-0">قائمة الطلاب</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                                <tr>
                                    <th>الاسم</th>
                                    <th>البريد</th>
                                    <th>الميلاد</th>
                                    <th>الرقم الوطني</th>
                                    <th>الرقم الدراسي</th>
                                    <th>الهاتف</th>
                                    <th>الجنس</th>
                                    <th class="text-center" style="width:180px">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr>
                                        <td>{{ $student->student_name }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ $student->dob?->format('Y-m-d') }}</td>
                                        <td>{{ $student->national_id }}</td>
                                        <td>{{ $student->student_number }}</td>
                                        <td>{{ $student->phone }}</td>
                                        <td>{{ $student->gender === 'male' ? 'ذكر' : ($student->gender === 'female' ? 'أنثى' : '-') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-secondary">تعديل</a>

                                            <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف هذا الطالب؟');">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">حذف</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">لا توجد بيانات بعد</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- /الجدول --}}
        </div>

</div>
@endsection
