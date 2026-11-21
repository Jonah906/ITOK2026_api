<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Rsvp;
use App\Models\Testimonial;
use App\Models\WeddingSetting;

class AdminController extends Controller
{
    public function dashboard()
    {
        return response()->json([
            'total_rsvps'         => Rsvp::count(),
            'virtual_guests'      => Rsvp::where('attendance', 'virtual')->count(),
            'total_messages'      => Chat::where('deleted', false)->count(),
            'total_testimonials'  => Testimonial::count(),
            'pending_testimonials'=> Testimonial::where('approved', false)->count(),
            'stream_live'         => (bool) optional(WeddingSetting::first())->live,
        ]);
    }

    public function rsvps()
    {
        return response()->json(Rsvp::latest()->get());
    }
}
