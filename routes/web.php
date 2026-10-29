<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TravelController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TravelController::class, 'index'])->name('home');
Route::get('/index', [TravelController::class, 'index'])->name('index');
Route::get('/about', [TravelController::class, 'about'])->name('about');

foreach (['elements', 'hotels', 'insurance', 'packages'] as $page) {
    Route::view('/'.$page, $page)->name($page);
}

Route::middleware('guest')->group(function () {
    Route::get('/auth_page', [AuthController::class, 'create'])->name('login');
    Route::post('/auth_page', [AuthController::class, 'store'])->middleware('throttle:10,1')->name('auth.store');
    Route::get('/verify', [AuthController::class, 'showVerification'])->name('verification.show');
    Route::post('/verify', [AuthController::class, 'verify'])->middleware('throttle:10,1')->name('verification.store');
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->middleware('throttle:10,1')->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
});

Route::get('/blog-home', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog-single', [BlogController::class, 'latest']);
Route::get('/blog-single/{post}', [BlogController::class, 'show'])->whereNumber('post')->name('blog.show');
Route::post('/blog-single/{post}', [BlogController::class, 'comment'])->whereNumber('post')->middleware('throttle:10,1')->name('blog.comment');

Route::get('/contact', [ContactController::class, 'create'])->name('contact.show');
Route::get('/api/user-contacts', [ContactController::class, 'testimonials'])->name('testimonials.index');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');
Route::post('/newsletter', [NewsletterController::class, 'store'])->middleware('throttle:5,1')->name('newsletter.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/setup_profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/setup_profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/add-blog', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/add-blog', [BlogController::class, 'store'])->middleware('throttle:10,1')->name('blog.store');
    Route::get('/connect', [ChatController::class, 'index'])->name('chat.index');

    // These JSON endpoints use session authentication and CSRF protection.
    Route::post('/api/get-or-create-thread/{user}', [ChatController::class, 'thread'])->middleware('throttle:60,1')->name('chat.thread');
    Route::get('/api/messages/{thread}', [ChatController::class, 'messages'])->name('chat.messages');
    Route::post('/api/messages/send', [ChatController::class, 'store'])->middleware('throttle:60,1')->name('chat.send');
});
