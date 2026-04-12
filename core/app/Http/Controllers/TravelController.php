<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TravelController extends Controller
{
    public function index()
    {
        // Example data passing
        $name = "Baku Traveler";
        return view('index', [
            'user_name' => $name,
        ]);
    }

    public function about()
    {
        return view('about');
    }

    public function add_blog()
    {
        // Note: Use underscores in method names even if route uses dashes
        return view('add-blog');
    }

    public function auth_page()
    {
        return view('auth_page');
    }

    public function blog_home()
    {
        return view('blog-home');
    }

    public function blog_single()
    {
        return view('blog-single');
    }

    public function connect()
    {
        return view('connect');
    }

    public function contact()
    {
        return view('contact');
    }

    public function elements()
    {
        return view('elements');
    }

    public function hotels()
    {
        return view('hotels');
    }

    public function insurance()
    {
        return view('insurance');
    }

    public function packages()
    {
        return view('packages');
    }

    public function profile()
    {
        return view('profile');
    }

    public function setup_profile()
    {
        return view('setup_profile');
    }

    public function verify()
    {
        return view('verify');
    }
}