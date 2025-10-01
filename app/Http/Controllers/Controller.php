<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Activity;
use App\Models\Announcement;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Staff;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

 public function index(Request $request)
    {
        $stats = [
            'students' => \App\Models\Student::count(),
            'teachers' => \App\Models\Teacher::count(),
            'staff'    => \App\Models\Staff::count(),
        ];

        $news = \App\Models\News::orderByRaw('COALESCE(published_at, created_at) DESC')
                    ->latest('id')->take(12)->get();

        $activities = \App\Models\Activity::orderBy('date','desc')->take(12)->get();

        $announcements = \App\Models\Announcement::orderByRaw('COALESCE(published_at, created_at) DESC')
                        ->latest('id')->take(12)->get();

        $schoolName = config('app.school_name', env('SCHOOL_NAME', 'مدرستنا'));
        $about = config('app.school_about', env('SCHOOL_ABOUT',
            'نظام مدرسي متكامل لإدارة الطلاب والمعلمين والموظفين مع أخبار وأنشطة وإعلانات محدثة.'
        ));

        $lat  = (float) config('app.school_lat',  env('SCHOOL_LAT', 15.3694));
        $lng  = (float) config('app.school_lng',  env('SCHOOL_LNG', 44.1910));
        $zoom = (int)   config('app.school_zoom', env('SCHOOL_ZOOM', 16));

        return view('welcome', compact(
            'stats','news','activities','announcements','schoolName','about','lat','lng','zoom'
        ));
    }
  public function index2()
    {
        return view('home');
    }

}
