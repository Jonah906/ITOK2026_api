<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\ReactionSent;
use App\Models\Chat;
use App\Models\Rsvp;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return response()->json(
            Chat::with('rsvp:id,name')
                ->where('deleted', false)
                ->latest()
                ->take(50)
                ->get()
                ->reverse()
                ->values()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'token'   => 'required|string',
            'message' => 'required|string|max:300',
        ]);

        $rsvp = Rsvp::where('stream_token', $request->token)->firstOrFail();

        $chat = Chat::create(['rsvp_id' => $rsvp->id, 'message' => $request->message]);
        $chat->load('rsvp:id,name');

        broadcast(new MessageSent($chat))->toOthers();

        return response()->json($chat, 201);
    }

    public function react(Request $request)
    {
        $request->validate(['emoji' => 'required|string|max:10', 'token' => 'required|string']);
        $rsvp = Rsvp::where('stream_token', $request->token)->firstOrFail();

        broadcast(new ReactionSent($request->emoji, $rsvp->name));

        return response()->json(['ok' => true]);
    }

    public function destroy(Chat $chat)
    {
        $chat->update(['deleted' => true]);
        return response()->json(['ok' => true]);
    }
}
