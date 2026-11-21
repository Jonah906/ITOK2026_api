<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class DonationController extends Controller
{
    public function index()
    {
        $secret = config('services.paystack.secret');

        if (!$secret) {
            return response()->json(['error' => 'Paystack secret key not configured.'], 500);
        }

        $response = Http::withToken($secret)
            ->get('https://api.paystack.co/transaction', [
                'status'   => 'success',
                'perPage'  => 100,
            ]);

        if (!$response->successful()) {
            return response()->json(['error' => 'Failed to fetch from Paystack.'], 502);
        }

        $transactions = collect($response->json('data', []))->map(fn($t) => [
            'id'         => $t['id'],
            'name'       => $t['customer']['first_name'] . ' ' . $t['customer']['last_name'],
            'email'      => $t['customer']['email'],
            'amount'     => $t['amount'] / 100, // kobo to naira
            'currency'   => $t['currency'],
            'paid_at'    => $t['paid_at'],
            'reference'  => $t['reference'],
        ]);

        return response()->json([
            'total'        => $transactions->sum('amount'),
            'count'        => $transactions->count(),
            'transactions' => $transactions->values(),
        ]);
    }
}
