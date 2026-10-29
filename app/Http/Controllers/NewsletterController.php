<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        if (is_string($request->input('EMAIL'))) {
            $request->merge(['EMAIL' => strtolower(trim($request->input('EMAIL')))]);
        }

        $data = $request->validate(['EMAIL' => ['required', 'email', 'max:255']]);
        NewsletterSubscription::firstOrCreate(['email' => $data['EMAIL']]);

        return back()->with('success', 'You have subscribed to the newsletter.');
    }
}
