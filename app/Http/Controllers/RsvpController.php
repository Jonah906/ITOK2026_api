<?php

namespace App\Http\Controllers;

use App\Mail\RsvpConfirmationMail;
use App\Models\Rsvp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RsvpController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:rsvps,email',
            'phone'      => 'nullable|string|max:20',
            'guests'     => 'integer|min:1|max:10',
            'attendance' => 'in:in-person,virtual',
        ]);

        $data['stream_token'] = Str::random(32);

        $rsvp = Rsvp::create($data);

        Mail::to($rsvp->email)->queue(new RsvpConfirmationMail($rsvp));

        $rsvp->update(['email_sent' => true]);

        return response()->json(['message' => 'RSVP confirmed! Check your email for your stream link.'], 201);
    }

    public function verify(string $token)
    {
        $rsvp = Rsvp::where('stream_token', $token)->firstOrFail();
        return response()->json(['name' => $rsvp->name, 'token' => $rsvp->stream_token]);
    }
}
