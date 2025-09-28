<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\GroupPostController;
use App\Http\Controllers\DonationCauseController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EventController;

use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CRMContactController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\EventAdminController;
use App\Http\Controllers\Admin\DonationAdminController;
use App\Http\Controllers\Admin\GroupAdminController;

// Admin dashboard (Fabkin analytics) + CRUD pages
Route::middleware(['auth','admin.only'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('dashboard')->group(function () {
        // Email
        Route::get('/email', [EmailController::class, 'index'])->name('dashboard.email');
        Route::post('/email', [EmailController::class, 'store'])->name('dashboard.email.store');
        Route::post('/email/{email}/read', [EmailController::class, 'markRead'])->name('dashboard.email.read');
        Route::delete('/email/{email}', [EmailController::class, 'destroy'])->name('dashboard.email.destroy');

        // Chat
        Route::get('/chat', [ChatController::class, 'index'])->name('dashboard.chat');
        Route::post('/chat', [ChatController::class, 'store'])->name('dashboard.chat.store');
        Route::delete('/chat/{message}', [ChatController::class, 'destroy'])->name('dashboard.chat.destroy');

        // Calendar (uses existing Event model)
        Route::get('/calendar', [CalendarController::class, 'index'])->name('dashboard.calendar');
        Route::post('/calendar', [CalendarController::class, 'store'])->name('dashboard.calendar.store');
        Route::delete('/calendar/{event}', [CalendarController::class, 'destroy'])->name('dashboard.calendar.destroy');

        // E-commerce
        Route::get('/ecommerce/products', [ProductController::class, 'index'])->name('dashboard.ecommerce.products');
        Route::post('/ecommerce/products', [ProductController::class, 'store'])->name('dashboard.ecommerce.products.store');
        Route::put('/ecommerce/products/{product}', [ProductController::class, 'update'])->name('dashboard.ecommerce.products.update');
        Route::delete('/ecommerce/products/{product}', [ProductController::class, 'destroy'])->name('dashboard.ecommerce.products.destroy');

        Route::get('/ecommerce/orders', [OrderController::class, 'index'])->name('dashboard.ecommerce.orders');
        Route::post('/ecommerce/orders', [OrderController::class, 'store'])->name('dashboard.ecommerce.orders.store');
        Route::put('/ecommerce/orders/{order}', [OrderController::class, 'update'])->name('dashboard.ecommerce.orders.update');
        Route::delete('/ecommerce/orders/{order}', [OrderController::class, 'destroy'])->name('dashboard.ecommerce.orders.destroy');
        Route::post('/ecommerce/orders/{order}/items', [OrderController::class, 'addItem'])->name('dashboard.ecommerce.orders.items.add');
        Route::delete('/ecommerce/orders/{order}/items/{item}', [OrderController::class, 'removeItem'])->name('dashboard.ecommerce.orders.items.remove');

        // Invoice (static page for now)
        Route::view('/invoice', 'admin.invoice-detail')->name('dashboard.invoice.detail');

        // CRM
        Route::get('/crm/contacts', [CRMContactController::class, 'index'])->name('dashboard.crm.contacts');
        Route::post('/crm/contacts', [CRMContactController::class, 'store'])->name('dashboard.crm.contacts.store');
        Route::put('/crm/contacts/{contact}', [CRMContactController::class, 'update'])->name('dashboard.crm.contacts.update');
        Route::delete('/crm/contacts/{contact}', [CRMContactController::class, 'destroy'])->name('dashboard.crm.contacts.destroy');

        // Academy
        Route::view('/academy/courses', 'admin.academy-courses')->name('dashboard.academy.courses');

        // CMS
        Route::view('/cms/blog', 'admin.cms-blog')->name('dashboard.cms.blog');

    // Admin: Users, Events, Donations (admin-only)
    Route::middleware('admin.only')->group(function () {
            Route::get('/admin/users', [UserAdminController::class, 'index'])->name('dashboard.admin.users');
            Route::put('/admin/users/{user}', [UserAdminController::class, 'update'])->name('dashboard.admin.users.update');
            Route::delete('/admin/users/{user}', [UserAdminController::class, 'destroy'])->name('dashboard.admin.users.destroy');

            Route::get('/admin/events', [EventAdminController::class, 'index'])->name('dashboard.admin.events');
            Route::put('/admin/events/{event}', [EventAdminController::class, 'update'])->name('dashboard.admin.events.update');
            Route::delete('/admin/events/{event}', [EventAdminController::class, 'destroy'])->name('dashboard.admin.events.destroy');

            Route::get('/admin/donations', [DonationAdminController::class, 'index'])->name('dashboard.admin.donations');
            Route::delete('/admin/donations/{donation}', [DonationAdminController::class, 'destroy'])->name('dashboard.admin.donations.destroy');

            // Admin: Groups
            Route::get('/admin/groups', [GroupAdminController::class, 'index'])->name('dashboard.admin.groups');
            Route::put('/admin/groups/{group}', [GroupAdminController::class, 'update'])->name('dashboard.admin.groups.update');
            Route::delete('/admin/groups/{group}', [GroupAdminController::class, 'destroy'])->name('dashboard.admin.groups.destroy');
        });
    });
});

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

Route::get('/donations', fn() => redirect()->route('donation-causes.index'))->name('donations');
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
       
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

    // --- Google OAuth routes ---
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google-callback', [AuthController::class, 'handleGoogleCallback']);

    // --- Facebook OAuth routes ---
    Route::get('/auth/facebook', [AuthController::class, 'redirectToFacebook'])->name('facebook.login');
    Route::get('/auth/facebook-callback', [AuthController::class, 'handleFacebookCallback']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Profile
Route::get('/profile', [ProfileController::class, 'show'])->name('profile')->middleware('auth');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy')->middleware('auth');

// Groups
Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create')->middleware('auth');
Route::post('/groups', [GroupController::class, 'store'])->name('groups.store')->middleware('auth');
Route::get('/groups/{slug}', [GroupController::class, 'show'])->name('groups.show');
// Donations
Route::resource('donation-causes', DonationCauseController::class);
Route::post('donations', [DonationController::class, 'store'])->name('donations.store')->middleware('auth');
// Membership
Route::post('/groups/{slug}/join', [MembershipController::class, 'join'])->name('groups.join')->middleware('auth');
Route::post('/groups/{slug}/leave', [MembershipController::class, 'leave'])->name('groups.leave')->middleware('auth');
Route::post('/groups/{slug}/requests/{requestId}/approve', [MembershipController::class, 'approve'])->name('groups.requests.approve')->middleware('auth');
Route::post('/groups/{slug}/requests/{requestId}/reject', [MembershipController::class, 'reject'])->name('groups.requests.reject')->middleware('auth');

// Posts
Route::post('/groups/{slug}/posts', [GroupPostController::class, 'store'])->name('groups.posts.store')->middleware('auth');
Route::post('/posts/{postId}/react', [GroupPostController::class, 'react'])->name('groups.posts.react')->middleware('auth');
Route::post('/posts/{postId}/comment', [GroupPostController::class, 'comment'])->name('groups.posts.comment')->middleware('auth');
Route::delete('/posts/{postId}', [GroupPostController::class, 'destroy'])->name('groups.posts.destroy')->middleware('auth');

// Notifications
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index')->middleware('auth');
Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read')->middleware('auth');
Route::post('/notifications/read-all', [NotificationController::class, 'markAll'])->name('notifications.readAll')->middleware('auth');

// Group manage (owner)
Route::get('/groups/{slug}/edit', [GroupController::class, 'edit'])->name('groups.edit')->middleware('auth');
Route::put('/groups/{slug}', [GroupController::class, 'update'])->name('groups.update')->middleware('auth');
Route::delete('/groups/{slug}', [GroupController::class, 'destroy'])->name('groups.destroy')->middleware('auth');

// Events
Route::get('/events/create', [EventController::class, 'create'])->name('events.create')->middleware('auth');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/events/create', [EventController::class, 'create'])->name('events.create')->middleware('auth');
Route::post('/events', [EventController::class, 'store'])->name('events.store');
Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
Route::post('/events/{event}/comment', [EventController::class, 'storeComment'])
    ->name('comments.store')->middleware('auth');

Route::delete('/comments/{comment}', [EventController::class, 'destroyComment'])
    ->name('comments.destroy')->middleware('auth');
