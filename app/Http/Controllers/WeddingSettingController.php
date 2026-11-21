<?php

namespace App\Http\Controllers;

use App\Models\WeddingSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WeddingSettingController extends Controller
{
    public function index()
    {
        $setting = WeddingSetting::first() ?? new WeddingSetting();

        // Append full URLs for stored videos
        $data = $setting->toArray();
        if ($setting->story_video && !str_starts_with($setting->story_video, 'http')) {
            $data['story_video'] = Storage::disk('public')->url($setting->story_video);
        }
        if (!empty($setting->gallery_videos)) {
            $data['gallery_videos'] = array_map(function ($v) {
                return str_starts_with($v, 'http') ? $v : Storage::disk('public')->url($v);
            }, $setting->gallery_videos);
        }

        return response()->json($data);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'bride_name'     => 'sometimes|string|max:100',
            'groom_name'     => 'sometimes|string|max:100',
            'wedding_date'   => 'sometimes|date',
            'venue'          => 'sometimes|string|max:200',
            'venue_address'  => 'sometimes|string',
            'dress_code'     => 'sometimes|string|max:100',
            'our_story'      => 'sometimes|string',
            'stream_url'     => 'sometimes|url',
            'gallery'        => 'sometimes|array',
            'gallery_videos' => 'sometimes|array',
            'live'           => 'sometimes|boolean',
        ]);

        $setting = WeddingSetting::firstOrCreate([]);
        $setting->update($data);

        return response()->json($setting);
    }

    public function uploadStoryVideo(Request $request)
    {
        $request->validate(['video' => 'required|file|mimetypes:video/mp4,video/webm,video/ogg|max:102400']);

        $setting = WeddingSetting::firstOrCreate([]);

        // Delete old video
        if ($setting->story_video && !str_starts_with($setting->story_video, 'http')) {
            Storage::disk('public')->delete($setting->story_video);
        }

        $path = $request->file('video')->store('videos/story', 'public');
        $setting->update(['story_video' => $path]);

        return response()->json(['url' => Storage::disk('public')->url($path)]);
    }

    public function uploadGalleryVideo(Request $request)
    {
        $request->validate(['video' => 'required|file|mimetypes:video/mp4,video/webm,video/ogg|max:102400']);

        $path = $request->file('video')->store('videos/gallery', 'public');
        $url  = Storage::disk('public')->url($path);

        $setting = WeddingSetting::firstOrCreate([]);
        $videos  = $setting->gallery_videos ?? [];
        $videos[] = $path;
        $setting->update(['gallery_videos' => $videos]);

        return response()->json(['url' => $url]);
    }

    public function deleteGalleryVideo(Request $request)
    {
        $request->validate(['path' => 'required|string']);

        $setting = WeddingSetting::firstOrCreate([]);
        $videos  = array_filter($setting->gallery_videos ?? [], fn($v) => $v !== $request->path);
        $setting->update(['gallery_videos' => array_values($videos)]);

        Storage::disk('public')->delete($request->path);

        return response()->json(null, 204);
    }

    public function uploadGalleryImage(Request $request)
    {
        $request->validate(['image' => 'required|file|image|max:10240']);

        $path = $request->file('image')->store('images/gallery', 'public');
        $url  = Storage::disk('public')->url($path);

        $setting = WeddingSetting::firstOrCreate([]);
        $gallery = $setting->gallery ?? [];
        $gallery[] = $url;
        $setting->update(['gallery' => $gallery]);

        return response()->json(['url' => $url]);
    }

    public function deleteGalleryImage(Request $request)
    {
        $request->validate(['url' => 'required|string']);

        $setting = WeddingSetting::firstOrCreate([]);
        $gallery = array_values(array_filter($setting->gallery ?? [], fn($u) => $u !== $request->url));
        $setting->update(['gallery' => $gallery]);

        // Delete file if stored locally
        $parsed = parse_url($request->url, PHP_URL_PATH);
        $path   = ltrim(str_replace('/storage/', '', $parsed), '/');
        if ($path) Storage::disk('public')->delete($path);

        return response()->json(null, 204);
    }
}
