<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Post;
use Illuminate\View\View;

class TravelController extends Controller
{
    public function index(): View
    {
        return view('index', ['recent_posts' => Post::with('tags')->latest('pub_date')->take(2)->get()]);
    }

    public function about(): View
    {
        $testimonials = ContactMessage::with('user')->where('is_published', true)->latest()->take(10)->get();

        return view('about', ['carousel_items' => $testimonials, 'dots_range' => $testimonials->keys()]);
    }
}
