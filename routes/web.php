<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\Inputs\CivilController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Inputs\NewsController;
use App\Http\Controllers\Inputs\ResultsController;
use App\Http\Controllers\Inputs\AnnouncementsController;
use App\Http\Controllers\Inputs\ActivitiesController;
use App\Http\Controllers\Inputs\StaffController;
use App\Http\Controllers\Inputs\TeachersController;
use App\Http\Controllers\Inputs\AttendanceController;
use App\Http\Controllers\Inputs\TeacherResultsController;
use App\Http\Controllers\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\Inputs\ResultsViewController;

use App\Http\Controllers\Controller;

Route::get('/', [Controller::class, 'index'])->name("homepage");


Auth::routes();







// كل ما يلي يتطلب تسجيل دخول وتحقق بريد (لو مفعّل)
//Route::middleware(['auth','verified'])->group(function () {
Route::middleware(['auth'])->group(function () {
Route::get('/home', [App\Http\Controllers\Controller::class, 'index2'])->name('home');

    // مجموعة inputs.civil.* (كما لديك)
    Route::prefix('inputs')->name('inputs.')->group(function () {

        Route::get('/civil',                 [CivilController::class, 'create'])->name('civil');
        Route::post('/civil',                [CivilController::class, 'store'])->name('civil.store');
        Route::get('/civil/{student}/edit',  [CivilController::class, 'edit'])->name('civil.edit');
        Route::put('/civil/{student}',       [CivilController::class, 'update'])->name('civil.update');
        Route::delete('/civil/{student}',    [CivilController::class, 'destroy'])->name('civil.destroy');
    });

    // ⚠️ روت توافقية بنفس الأسماء القديمة students.* لتفادي RouteNotFound
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/create', fn() => redirect()->route('inputs.civil'))->name('create');
        Route::post('/store', fn() => redirect()->route('inputs.civil.store'))->name('store');

        Route::get('/{student}/edit',  [CivilController::class, 'edit'])->name('edit');
        Route::put('/{student}',       [CivilController::class, 'update'])->name('update');
        Route::delete('/{student}',    [CivilController::class, 'destroy'])->name('destroy');
    });



    // Inputs (مجموعة المدخلات من القائمة المنسدلة)
    Route::prefix('inputs')->name('inputs.')->group(function () {

        Route::get('/timetables', [TimetableController::class, 'index'])->name('timetables');
        Route::post('/timetables', [TimetableController::class, 'store'])->name('timetables.store');
        // Blade فقط الآن: أنشئ قوالب مؤقتة بنفس المسارات المذكورة
                  // إدخال البيانات تسجيل المدني
        // إدخال الجداول
      //  Route::view('/results',      'pages/inputs/results')->name('results');           // إدخال نتيجة الطلاب
        Route::view('/news',         'pages/inputs/news')->name('news');                 // إدخال الأخبار
        Route::view('/announcements','pages/inputs/announcements')->name('announcements');// إدخال الإعلانات
        Route::view('/activities',   'pages/inputs/activities')->name('activities');     // إدخال النشاطات
        Route::view('/staff',        'pages/inputs/staff')->name('staff');               // إضافة الموظفين
        Route::view('/attendance',   'pages/inputs/attendance')->name('attendance');     // إدخال الحضور
        Route::view('/teachers',     'pages/inputs/teachers')->name('teachers');         // إدخال بيانات المعلمين
    });

    Route::middleware(['auth'])
    ->prefix('inputs')
    ->name('inputs.')
    ->group(function () {

        // عرض النموذج + تحميل المواد بحسب التخصص/الفصل/السنة
        Route::get('/results', [ResultsController::class, 'index'])->name('results');
        // حفظ/تحديث الدرجات
        Route::post('/results', [ResultsController::class, 'save'])->name('results.save');

    });
Route::prefix('profile')->name('profile.')->group(function () {
        // صفحة تعديل الحساب
        Route::get('edit', [ProfileController::class, 'edit'])->name('edit');
        // حفظ التعديل
        Route::put('update', [ProfileController::class, 'update'])->name('update');
    });

    // Admin → Users (CRUD)
    // ملاحظة: لو ما عندك ميدلوير 'admin' حالياً، احذف 'admin' من السطر التالي مؤقتاً
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/',           [AdminUserController::class, 'index'])->name('index');
            Route::get('/create',     [AdminUserController::class, 'create'])->name('create');
            Route::post('/',          [AdminUserController::class, 'store'])->name('store');
            Route::get('/{user}/edit',[AdminUserController::class, 'edit'])->name('edit');
            Route::put('/{user}',     [AdminUserController::class, 'update'])->name('update');
            Route::delete('/{user}',  [AdminUserController::class, 'destroy'])->name('destroy');
        });
    });


Route::middleware(['auth'])->prefix('inputs')->name('inputs.')->group(function () {

    Route::get('/news',            [NewsController::class, 'index'])->name('news');
    Route::post('/news',           [NewsController::class, 'store'])->name('news.store');
    Route::get('/news/{news}/edit',[NewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{news}',     [NewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{news}',  [NewsController::class, 'destroy'])->name('news.destroy');

    Route::get('/announcements',                   [AnnouncementsController::class, 'index'])->name('announcements');
    Route::post('/announcements',                  [AnnouncementsController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{announcement}/edit',[AnnouncementsController::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/{announcement}',    [AnnouncementsController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [AnnouncementsController::class, 'destroy'])->name('announcements.destroy');

    Route::get('/activities',                   [ActivitiesController::class, 'index'])->name('activities');
    Route::post('/activities',                  [ActivitiesController::class, 'store'])->name('activities.store');
    Route::get('/activities/{activity}/edit',   [ActivitiesController::class, 'edit'])->name('activities.edit');
    Route::put('/activities/{activity}',        [ActivitiesController::class, 'update'])->name('activities.update');
    Route::delete('/activities/{activity}',     [ActivitiesController::class, 'destroy'])->name('activities.destroy');

    Route::get('/staff',               [StaffController::class, 'index'])->name('staff');
    Route::post('/staff',              [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{staff}/edit',  [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{staff}',       [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{staff}',    [StaffController::class, 'destroy'])->name('staff.destroy');

    Route::get('/teachers',                 [TeachersController::class, 'index'])->name('teachers');
    Route::post('/teachers',                [TeachersController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{teacher}/edit',  [TeachersController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{teacher}',       [TeachersController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{teacher}',    [TeachersController::class, 'destroy'])->name('teachers.destroy');

    Route::get('/attendance',                [AttendanceController::class, 'index'])->name('attendance');
    Route::post('/attendance',               [AttendanceController::class, 'store'])->name('attendance.store');
    Route::delete('/attendance/{attendance}',[AttendanceController::class, 'destroy'])->name('attendance.destroy');

     // اختيار المعلّم والمادة ثم تحميل الطلاب + إدخال الدرجات
    Route::get('/teacher-results',  [TeacherResultsController::class, 'index'])->name('teacher_results');
    Route::post('/teacher-results', [TeacherResultsController::class, 'store'])->name('teacher_results.save');

     // إدخال درجة لطالب واحد حسب المعلّم/المقرر
    Route::get('/teacher-result',  [TeacherResultsController::class, 'single'])->name('teacher_result.single');
    Route::post('/teacher-result', [TeacherResultsController::class, 'singleStore'])->name('teacher_result.single.save');

    // (اختياري) إبقاء اسم قديم متوافق إن كان مستخدمًا في الواجهات

});

Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/users',                   [AdminUsersController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/role',     [AdminUsersController::class, 'updateRole'])->name('users.role');
        Route::patch('/users/{user}/password', [AdminUsersController::class, 'resetPassword'])->name('users.reset_password');
        Route::delete('/users/{user}',         [AdminUsersController::class, 'destroy'])->name('users.destroy');
    });

    Route::middleware(['auth'])
    ->prefix('results')
    ->name('results.')
    ->group(function () {
        Route::get('/student', [ResultsViewController::class, 'student'])->name('student');
    });



Route::get('/certificates/create', [CertificateController::class, 'create'])->name('certificates.create');
Route::post('/certificates', [CertificateController::class, 'store'])->name('certificates.store');
Route::get('/certificates/{code}', [CertificateController::class, 'show'])->name('certificates.show');
Route::get('/certificates/{code}/pdf', [CertificateController::class, 'pdf'])->name('certificates.pdf');

Route::get('/certificates/search/sss', [CertificateController::class, 'search'])->name('certificates.search');

Route::get('/certificates/find/ds', function(\Illuminate\Http\Request $request){
    $code = $request->query('code');
    return redirect()->route('certificates.show',$code);
})->name('certificates.showByCode');

Route::delete('certificates/{id}', [CertificateController::class,'destroy'])
    ->name('certificates.destroy');


Route::get('/_mpdf_check', function () {
    return class_exists(\Mpdf\Mpdf::class) ? 'OK' : 'NO';
});

});
//});
