<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        return view('profile', [
            'profile_user' => $user,
            'photo_count' => $user->posts()->whereNotNull('blog_img')->count(),
            'post_count' => $user->posts()->count(),
            'comment_count' => $user->comments()->count(),
            'comments' => $user->comments()->with(['post', 'user'])->latest()->get(),
        ]);
    }

    public function edit(Request $request): View
    {
        return view('setup_profile', ['profile_user' => $request->user()]);
    }

    public function update(ProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->safe()->except('profile_img');
        $oldImage = $user->profile_img;
        $newImage = $request->file('profile_img')?->store('profiles', 'public');

        if ($newImage) {
            $data['profile_img'] = $newImage;
        }

        try {
            $user->update($data);
        } catch (\Throwable $exception) {
            if ($newImage) {
                Storage::disk('public')->delete($newImage);
            }

            throw $exception;
        }

        if ($newImage && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return to_route('profile.show');
    }
}
