<?php

namespace App\Http\Controllers\Inputs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\News;

class NewsController extends Controller
{
    /**
     * صفحة إضافة خبر + عرض الأخبار أسفلها.
     */
    public function index(Request $request)
    {
        $news = News::latest('published_at')->latest('id')->paginate(10);
        return view('pages.inputs.news', compact('news'));
    }

    /**
     * حفظ خبر جديد (وسائط اختيارية).
     */
    public function store(Request $request)
    {
        $rules = [
            'title'        => ['required','string','max:255'],
            'published_at' => ['nullable','date'],
            'excerpt'      => ['nullable','string','max:500'],
            'body'         => ['required','string'],
            'category'     => ['nullable', Rule::in(['عام','طلاب','معلمون','إعلانات'])],
            'media_type'   => ['nullable','in:image,video'], // اختياري
        ];

        // التحقق من نوع/حجم الملف حسب الاختيار
        if ($request->input('media_type') === 'video') {
            $rules['media_file'] = ['nullable','file','mimes:mp4,webm,ogg','max:20480']; // 20MB
        } else {
            $rules['media_file'] = ['nullable','file','mimes:jpg,jpeg,png,webp,gif','max:5120']; // 5MB
        }

        $data = $request->validate($rules, [
            'title.required' => 'عنوان الخبر مطلوب.',
            'body.required'  => 'تفاصيل الخبر مطلوبة.',
        ]);

        if (empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        // رفع الوسائط (اختياري)
        $mediaPath = null;
        $mediaType = $request->input('media_type'); // قد تكون null
        if ($request->hasFile('media_file')) {
            $sub       = ($mediaType === 'video') ? 'news/videos' : 'news/images';
            $mediaPath = $request->file('media_file')->store($sub, 'public'); // public/storage/...
        }

        News::create([
            'title'        => $data['title'],
            'published_at' => $data['published_at'],
            'excerpt'      => $data['excerpt'] ?? null,
            'body'         => $data['body'],
            'category'     => $data['category'] ?? null,
            'media_type'   => $mediaType,   // image|video|null
            'media_path'   => $mediaPath,   // مسار الملف أو null
            'created_by'   => auth()->id(),
        ]);

        return back()->with('success', 'تم حفظ الخبر بنجاح ✅');
    }

    /**
     * صفحة تعديل خبر.
     */
    public function edit(News $news)
    {
        return view('pages.inputs.news_edit', compact('news'));
    }

    /**
     * تحديث خبر (مع إمكانية استبدال الوسائط).
     */
    public function update(Request $request, News $news)
    {
        $rules = [
            'title'        => ['required','string','max:255'],
            'published_at' => ['nullable','date'],
            'excerpt'      => ['nullable','string','max:500'],
            'body'         => ['required','string'],
            'category'     => ['nullable', Rule::in(['عام','طلاب','معلمون','إعلانات'])],
            'media_type'   => ['nullable','in:image,video'],
        ];

        if ($request->input('media_type') === 'video') {
            $rules['media_file'] = ['nullable','file','mimes:mp4,webm,ogg','max:20480'];
        } else {
            $rules['media_file'] = ['nullable','file','mimes:jpg,jpeg,png,webp,gif','max:5120'];
        }

        $data = $request->validate($rules, [
            'title.required' => 'عنوان الخبر مطلوب.',
            'body.required'  => 'تفاصيل الخبر مطلوبة.',
        ]);

        // تحديث الحقول الأساسية
        $news->fill([
            'title'        => $data['title'],
            'published_at' => $data['published_at'] ?? $news->published_at,
            'excerpt'      => $data['excerpt'] ?? null,
            'body'         => $data['body'],
            'category'     => $data['category'] ?? null,
            'media_type'   => $request->input('media_type'), // قد تُغيَّر
        ]);

        // استبدال الملف (اختياري)
        if ($request->hasFile('media_file')) {
            if ($news->media_path) {
                Storage::disk('public')->delete($news->media_path);
            }
            $sub             = ($news->media_type === 'video') ? 'news/videos' : 'news/images';
            $news->media_path = $request->file('media_file')->store($sub, 'public');
        }

        $news->save();

        return redirect()->route('inputs.news')->with('success', 'تم تحديث الخبر بنجاح ✅');
    }

    /**
     * حذف خبر (مع حذف الوسائط من التخزين).
     */
    public function destroy(News $news)
    {
        if ($news->media_path) {
            Storage::disk('public')->delete($news->media_path);
        }
        $news->delete();

        return back()->with('success', 'تم حذف الخبر بنجاح 🗑️');
    }
}
