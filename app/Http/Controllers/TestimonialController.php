<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index()
    {
        return response()->json(
            Testimonial::where('approved', true)->latest()->get()->map(fn($t) => $this->format($t))
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'relation' => 'nullable|string|max:100',
            'message'  => 'required|string|max:1000',
        ]);

        $data['type'] = 'text';
        return response()->json(Testimonial::create($data), 201);
    }

    public function storeVideo(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'relation' => 'nullable|string|max:100',
            'video'    => 'required|file|mimetypes:video/mp4,video/webm,video/ogg|max:102400',
        ]);

        $path = $request->file('video')->store('videos/testimonials', 'public');

        $testimonial = Testimonial::create([
            'name'       => $request->name,
            'relation'   => $request->relation,
            'type'       => 'video',
            'video_path' => $path,
        ]);

        return response()->json($this->format($testimonial), 201);
    }

    public function all()
    {
        return response()->json(
            Testimonial::latest()->get()->map(fn($t) => $this->format($t))
        );
    }

    public function approve(Testimonial $testimonial)
    {
        $testimonial->update(['approved' => !$testimonial->approved]);
        return response()->json($this->format($testimonial));
    }

    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->video_path) {
            Storage::disk('public')->delete($testimonial->video_path);
        }
        $testimonial->delete();
        return response()->json(null, 204);
    }

    private function format(Testimonial $t): array
    {
        $data = $t->toArray();
        if ($t->video_path && !str_starts_with($t->video_path, 'http')) {
            $data['video_url'] = Storage::disk('public')->url($t->video_path);
        }
        return $data;
    }
}
