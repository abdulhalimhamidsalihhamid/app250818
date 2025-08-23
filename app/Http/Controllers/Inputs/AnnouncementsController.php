<?php

namespace App\Http\Controllers\Inputs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Announcement;

class AnnouncementsController extends Controller
{
    /**
     * صفحة الإدخال + عرض الإعلانات أسفلها.
     */
    public function index(Request $request)
    {
        $announcements = Announcement::latest('published_at')->latest('id')->paginate(8);
        return view('pages.inputs.announcements', compact('announcements'));
    }

    /**
     * حفظ إعلان جديد.
     */
    public function store(Request $request)
    {
        $rules = [
            'title'        => ['required','string','max:255'],
            'body'         => ['required','string'],
            'published_at' => ['nullable','date'],
            'expires_at'   => ['nullable','date','after_or_equal:published_at'],
            'audience'     => ['nullable', Rule::in(['الكل','طلاب','معلمون','موظفون'])],
            'media_type'   => ['nullable','in:image,video'],
        ];

        // حجم/نوع الملف حسب الاختيار
        if ($request->input('media_type') === 'video') {
            $rules['media_file'] = ['nullable','file','mimes:mp4,webm,ogg','max:20480']; // 20MB
        } else {
            $rules['media_file'] = ['nullable','file','mimes:jpg,jpeg,png,webp,gif','max:5120']; // 5MB
        }

        $data = $request->validate($rules, [
            'title.required' => 'عنوان الإعلان مطلوب.',
            'body.required'  => 'نص الإعلان مطلوب.',
        ]);

        if (empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        // رفع الوسائط (اختياري)
        $mediaPath = null;
        $mediaType = $request->input('media_type');
        if ($request->hasFile('media_file')) {
            $sub       = ($mediaType === 'video') ? 'announcements/videos' : 'announcements/images';
            $mediaPath = $request->file('media_file')->store($sub, 'public');
        }

        Announcement::create([
            'title'        => $data['title'],
            'body'         => $data['body'],
            'published_at' => $data['published_at'],
            'expires_at'   => $data['expires_at'] ?? null,
            'audience'     => $data['audience'] ?? null,
            'media_type'   => $mediaType,
            'media_path'   => $mediaPath,
            'created_by'   => auth()->id(),
        ]);

        return back()->with('success', 'تم حفظ الإعلان بنجاح ✅');
    }

    /**
     * صفحة تعديل إعلان.
     */
    public function edit(Announcement $announcement)
    {
        return view('pages.inputs.announcements_edit', compact('announcement'));
    }

    /**
     * تحديث إعلان.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $rules = [
            'title'        => ['required','string','max:255'],
            'body'         => ['required','string'],
            'published_at' => ['nullable','date'],
            'expires_at'   => ['nullable','date','after_or_equal:published_at'],
            'audience'     => ['nullable', Rule::in(['الكل','طلاب','معلمون','موظفون'])],
            'media_type'   => ['nullable','in:image,video'],
        ];

        if ($request->input('media_type') === 'video') {
            $rules['media_file'] = ['nullable','file','mimes:mp4,webm,ogg','max:20480'];
        } else {
            $rules['media_file'] = ['nullable','file','mimes:jpg,jpeg,png,webp,gif','max:5120'];
        }

        $data = $request->validate($rules, [
            'title.required' => 'عنوان الإعلان مطلوب.',
            'body.required'  => 'نص الإعلان مطلوب.',
        ]);

        $announcement->fill([
            'title'        => $data['title'],
            'body'         => $data['body'],
            'published_at' => $data['published_at'] ?? $announcement->published_at,
            'expires_at'   => $data['expires_at'] ?? null,
            'audience'     => $data['audience'] ?? null,
            'media_type'   => $request->input('media_type'),
        ]);

        // استبدال الوسائط عند الرفع
        if ($request->hasFile('media_file')) {
            if ($announcement->media_path) {
                Storage::disk('public')->delete($announcement->media_path);
            }
            $sub = ($announcement->media_type === 'video') ? 'announcements/videos' : 'announcements/images';
            $announcement->media_path = $request->file('media_file')->store($sub, 'public');
        }

        $announcement->save();

        return redirect()->route('inputs.announcements')->with('success', 'تم تحديث الإعلان بنجاح ✅');
    }

    /**
     * حذف إعلان.
     */
    public function destroy(Announcement $announcement)
    {
        if ($announcement->media_path) {
            Storage::disk('public')->delete($announcement->media_path);
        }
        $announcement->delete();

        return back()->with('success', 'تم حذف الإعلان بنجاح 🗑️');
    }
}
