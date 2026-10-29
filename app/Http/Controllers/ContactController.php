<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function testimonials(): JsonResponse
    {
        return response()->json(ContactMessage::with('user')->where('is_published', true)->latest()->take(10)->get()
            ->map(fn ($message) => [
                'name' => $message->name,
                'surname' => $message->surname,
                'message' => $message->message,
                'stars' => $message->stars,
                'user_img' => $message->user?->profile_image_url ?? asset('images/user-img/user1.png'),
            ]));
    }

    public function create(): View
    {
        return view('contact', collect(['name', 'surname', 'email', 'subject', 'message'])
            ->mapWithKeys(fn ($field) => [$field => old($field, '')])->all());
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        ContactMessage::create([...$request->validated(), 'user_id' => $request->user()?->id]);

        return to_route('contact.show')->with('success', 'Your message has been received.');
    }
}
