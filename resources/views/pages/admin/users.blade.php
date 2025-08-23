@extends('layouts.app')

@section('content')
<div class="container py-4" dir="rtl">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex align-items-center gap-2">
            <strong>إدارة المستخدمين</strong>
            <form method="GET" class="ms-auto d-flex gap-2">
                <input type="text" name="q" class="form-control form-control-sm" value="{{ $s ?? '' }}" placeholder="بحث بالاسم/البريد/الرقم الوطني">
                <button class="btn btn-sm btn-outline-secondary">بحث</button>
            </form>
        </div>
        <div class="card-body">
            @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
                </div>
            @endif

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>البريد</th>
                            <th>الرقم الوطني</th>
                            <th>الدور</th>
                            <th class="text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                            <tr>
                                <td>{{ $u->id }}</td>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td>{{ $u->national_id ?? '-' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.users.role', $u) }}" class="d-inline-flex align-items-center gap-2">
                                        @csrf @method('PATCH')
                                        <select name="role" class="form-select form-select-sm">
                                            @foreach(['admin'=>'مشرف','teacher'=>'معلم','student'=>'طالب','staff'=>'موظف'] as $val=>$label)
                                                <option value="{{ $val }}" {{ $u->role===$val?'selected':'' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-sm btn-outline-primary">حفظ</button>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="d-inline" onsubmit="return confirm('حذف المستخدم؟');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
