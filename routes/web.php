<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;


// Render Blade homepage (Vite + Blade)
Route::get('/', function () {
    return view('pages.home');
})->name('home');

// Static content pages via Blade
Route::view('/about', 'pages.about')->name('about');
Route::view('/services', 'pages.services')->name('services');
Route::view('/contact', 'pages.contact')->name('contact');

// Selected pages from template (one variant per section)
Route::view('/projects', 'pages.projects')->name('projects');
Route::view('/project', 'pages.project-single')->name('project.single');

Route::view('/donations', 'pages.donations')->name('donations');
Route::view('/donation', 'pages.donation-single')->name('donation.single');

Route::view('/blog', 'pages.blog')->name('blog');
Route::view('/blog-post', 'pages.blog-single')->name('blog.single');

Route::view('/events', 'pages.events')->name('events');
Route::view('/event', 'pages.event-single')->name('event.single');

Route::view('/team', 'pages.team')->name('team');
Route::view('/team-member', 'pages.team-single')->name('team.single');

Route::view('/shop', 'pages.shop')->name('shop');
Route::view('/product', 'pages.product')->name('product');
Route::view('/cart', 'pages.cart')->name('cart');
Route::view('/checkout', 'pages.checkout')->name('checkout');

Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/error', 'pages.error-page')->name('error.page');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Profile
Route::get('/profile', [ProfileController::class, 'show'])->name('profile')->middleware('auth');