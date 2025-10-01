<?php

namespace App\Http\Controllers\Inputs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Activity;

class ActivitiesController extends Controller
{
    public function index(Request $request)
    {
        $activities = Activity::latest('date')->latest('id')->paginate(8);
        return view('pages.inputs.activities', compact('activities'));
    }

    public function store(Request $request)
    {
        $rules = [
            'title'      => ['required','string','max:255'],
            'date'       => ['required','date'],
            'location'   => ['nullable','string','max:255'],
            'category'   => ['nullable','string','max:50'], // رياضي/ثقافي/رحلة... حسب رغبتك
            'description'=> ['required','string'],
            'media_type' => ['nullable','in:image,video'],
        ];
        if ($request->input('media_type') === 'video') {
            $rules['media_file'] = ['nullable','file','mimes:mp4,webm,ogg','max:20480']; // 20MB
        } else {
            $rules['media_file'] = ['nullable','file','mimes:jpg,jpeg,png,webp,gif','max:5120']; // 5MB
        }

        $data = $request->validate($rules, [
            'title.required'       => 'عنوان النشاط مطلوب.',
            'date.required'        => 'تاريخ النشاط مطلوب.',
            'description.required' => 'وصف النشاط مطلوب.',
        ]);

        $mediaPath = null;
        $mediaType = $request->input('media_type');
        if ($request->hasFile('media_file')) {
            $sub       = ($mediaType === 'video') ? 'activities/videos' : 'activities/images';
            $mediaPath = $request->file('media_file')->store($sub, 'public');
        }

        Activity::create([
            'title'       => $data['title'],
            'date'        => $data['date'],
            'location'    => $data['location'] ?? null,
            'category'    => $data['category'] ?? null,
            'description' => $data['description'],
            'media_type'  => $mediaType,
            'media_path'  => $mediaPath,
            'created_by'  => auth()->id(),
        ]);

        return back()->with('success','تم حفظ النشاط بنجاح ✅');
    }

    public function edit(Activity $activity)
    {
        return view('pages.inputs.activities_edit', compact('activity'));
    }

    public function update(Request $request, Activity $activity)
    {
        $rules = [
            'title'      => ['required','string','max:255'],
            'date'       => ['required','date'],
            'location'   => ['nullable','string','max:255'],
            'category'   => ['nullable','string','max:50'],
            'description'=> ['required','string'],
            'media_type' => ['nullable','in:image,video'],
        ];
        if ($request->input('media_type') === 'video') {
            $rules['media_file'] = ['nullable','file','mimes:mp4,webm,ogg','max:20480'];
        } else {
            $rules['media_file'] = ['nullable','file','mimes:jpg,jpeg,png,webp,gif','max:5120'];
        }

        $data = $request->validate($rules);

        $activity->fill([
            'title'       => $data['title'],
            'date'        => $data['date'],
            'location'    => $data['location'] ?? null,
            'category'    => $data['category'] ?? null,
            'description' => $data['description'],
            'media_type'  => $request->input('media_type'),
        ]);

        if ($request->hasFile('media_file')) {
            if ($activity->media_path) Storage::disk('public')->delete($activity->media_path);
            $sub = ($activity->media_type === 'video') ? 'activities/videos' : 'activities/images';
            $activity->media_path = $request->file('media_file')->store($sub, 'public');
        }

        $activity->save();

        return redirect()->route('inputs.activities')->with('success','تم تحديث النشاط بنجاح ✅');
    }

    public function destroy(Activity $activity)
    {
        if ($activity->media_path) Storage::disk('public')->delete($activity->media_path);
        $activity->delete();
        return back()->with('success','تم حذف النشاط بنجاح 🗑️');
    }
}
