<?php

namespace App\Http\Controllers;

use App\Models\Rsvp;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AccessCodeController extends Controller
{
    // List all RSVPs with their access codes & QR codes
    public function index()
    {
        $rsvps = Rsvp::latest()->get();

        // Backfill any legacy records that are missing a code/QR (runs once per record, never again)
        $rsvps->each(function ($r) {
            if (!$r->access_code || !$r->qr_code) {
                if (!$r->access_code) {
                    $r->access_code = 'ITOK' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
                }
                $r->qr_code = base64_encode(QrCode::format('svg')->size(200)->generate($r->access_code));
                $r->saveQuietly();
            }
        });

        return response()->json($rsvps->map(fn($r) => $this->format($r)));
    }

    // Create a new invite (RSVP) with access code & QR
    public function create(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'nullable|email|max:255',
            'guests' => 'required|integer|min:1|max:10',
        ]);

        $code = 'ITOK' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        $qr   = base64_encode(QrCode::format('svg')->size(200)->generate($code));

        $rsvp = Rsvp::create([
            'name'         => $request->name,
            'email'        => $request->email ?? '',
            'phone'        => '',
            'guests'       => $request->guests,
            'attendance'   => 'in-person',
            'stream_token' => strtoupper(Str::random(16)),
            'access_code'  => $code,
            'qr_code'      => $qr,
            'scanned'      => false,
        ]);

        return response()->json($this->format($rsvp), 201);
    }

    // Validate / scan an access code
    public function scan(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $rsvp = Rsvp::where('access_code', strtoupper(trim($request->code)))->first();

        if (!$rsvp) {
            return response()->json(['valid' => false, 'message' => 'Invalid access code.'], 404);
        }

        if ($rsvp->scanned) {
            return response()->json([
                'valid'      => false,
                'message'    => 'This code has already been used.',
                'name'       => $rsvp->name,
                'scanned_at' => $rsvp->scanned_at,
            ], 409);
        }

        $rsvp->update(['scanned' => true, 'scanned_at' => now()]);

        return response()->json([
            'valid'   => true,
            'message' => 'Access granted!',
            'name'    => $rsvp->name,
            'guests'  => $rsvp->guests,
        ]);
    }

    // Reset a scanned code (admin use)
    public function reset(Rsvp $rsvp)
    {
        $rsvp->update(['scanned' => false, 'scanned_at' => null]);
        return response()->json(['message' => 'Reset successful.']);
    }

    private function format(Rsvp $r): array
    {
        return [
            'id'          => $r->id,
            'name'        => $r->name,
            'email'       => $r->email,
            'phone'       => $r->phone,
            'guests'      => $r->guests,
            'attendance'  => $r->attendance,
            'access_code' => $r->access_code,
            'qr_code'     => $r->qr_code,
            'scanned'     => $r->scanned,
            'scanned_at'  => $r->scanned_at,
            'created_at'  => $r->created_at,
        ];
    }
}
