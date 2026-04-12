<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TravelController;

Route::get('/about', [TravelController::class, 'about']);

Route::get('/add-blog', [TravelController::class, 'add_blog']);

Route::get('/auth_page', [TravelController::class,'auth_page']);

Route::get('/blog-home', [TravelController::class,'blog_home']);

Route::get('/blog-single', [TravelController::class,'blog_single']);

Route::get('/connect', [TravelController::class,'connect']);

Route::get('/contact', [TravelController::class,'contact']);

Route::get('/elements', [TravelController::class,'elements']);

Route::get('/hotels', [TravelController::class,'hotels']);

Route::get('/index', [TravelController::class,'index']);

Route::get('/insurance', [TravelController::class,'insurance']);

Route::get('/packages', [TravelController::class,'packages']);

Route::get('/profile', [TravelController::class,'profile']);

Route::get('/setup_profile', [TravelController::class,'setup_profile']);

Route::get('/verify', [TravelController::class,'verify']);
