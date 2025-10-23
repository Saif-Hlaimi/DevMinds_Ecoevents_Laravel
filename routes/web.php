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
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ComplaintTypeController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CRMContactController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\EventAdminController;
use App\Http\Controllers\Admin\DonationCauseAdminController;
use App\Http\Controllers\Admin\GroupAdminController;
use App\Http\Controllers\Api\GroupToolsController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\Admin\ComplaintAdminController;



 



use App\Http\Controllers\ChatbotController; // Ajout du contrôleur Chatbot

// Admin dashboard (Fabkin analytics) + CRUD pages
Route::middleware(['auth', 'admin.only'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('dashboard')->group(function () {
    // (Removed misplaced public Events routes from admin dashboard scope)
        Route::get('/calendar', [CalendarController::class, 'index'])->name('dashboard.calendar');
        Route::post('/calendar', [CalendarController::class, 'store'])->name('dashboard.calendar.store');
        Route::delete('/calendar/{event}', [CalendarController::class, 'destroy'])->name('dashboard.calendar.destroy');

    // Email
    Route::get('/email', [EmailController::class, 'index'])->name('dashboard.email');
    Route::post('/email', [EmailController::class, 'store'])->name('dashboard.email.store');
    Route::post('/email/{email}/read', [EmailController::class, 'markRead'])->name('dashboard.email.read');
    Route::delete('/email/{email}', [EmailController::class, 'destroy'])->name('dashboard.email.destroy');

    // Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('dashboard.chat');
    Route::post('/chat', [ChatController::class, 'store'])->name('dashboard.chat.store');
    Route::delete('/chat/{message}', [ChatController::class, 'destroy'])->name('dashboard.chat.destroy');

        // E-commerce
        Route::get('/ecommerce/products', [AdminProductController::class, 'index'])->name('dashboard.ecommerce.products');
        Route::post('/ecommerce/products', [AdminProductController::class, 'store'])->name('dashboard.ecommerce.products.store');
        Route::put('/ecommerce/products/{product}', [AdminProductController::class, 'update'])->name('dashboard.ecommerce.products.update');
        Route::delete('/ecommerce/products/{product}', [AdminProductController::class, 'destroy'])->name('dashboard.ecommerce.products.destroy');
        Route::get('/ecommerce/products/export-pdf', [AdminProductController::class, 'exportPdf'])->name('dashboard.ecommerce.products.export-pdf');

        Route::get('/ecommerce/orders', [AdminOrderController::class, 'index'])->name('dashboard.ecommerce.orders');
        Route::get('/ecommerce/orders/{order}', [AdminOrderController::class, 'show'])->name('dashboard.ecommerce.orders.show');
        Route::post('/ecommerce/orders', [AdminOrderController::class, 'store'])->name('dashboard.ecommerce.orders.store');
        Route::put('/ecommerce/orders/{order}', [AdminOrderController::class, 'update'])->name('dashboard.ecommerce.orders.update');
        Route::delete('/ecommerce/orders/{order}', [AdminOrderController::class, 'destroy'])->name('dashboard.ecommerce.orders.destroy');
        Route::post('/ecommerce/orders/{order}/items', [AdminOrderController::class, 'addItem'])->name('dashboard.ecommerce.orders.items.add');
        Route::delete('/ecommerce/orders/{order}/items/{item}', [AdminOrderController::class, 'removeItem'])->name('dashboard.ecommerce.orders.items.remove');
        
        // Additional order management routes
        Route::post('/ecommerce/orders/{order}/approve', [AdminOrderController::class, 'approve'])->name('dashboard.ecommerce.orders.approve');
        Route::post('/ecommerce/orders/{order}/reject', [AdminOrderController::class, 'reject'])->name('dashboard.ecommerce.orders.reject');
        Route::post('/ecommerce/orders/{order}/ship', [AdminOrderController::class, 'ship'])->name('dashboard.ecommerce.orders.ship');
        Route::post('/ecommerce/orders/{order}/deliver', [AdminOrderController::class, 'deliver'])->name('dashboard.ecommerce.orders.deliver');

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

        // Admin: Users, Events, Donations, Groups
        Route::get('/admin/users', [UserAdminController::class, 'index'])->name('dashboard.admin.users');
        Route::put('/admin/users/{user}', [UserAdminController::class, 'update'])->name('dashboard.admin.users.update');
        Route::delete('/admin/users/{user}', [UserAdminController::class, 'destroy'])->name('dashboard.admin.users.destroy');

        Route::get('/admin/events', [EventAdminController::class, 'index'])->name('dashboard.admin.events');
        Route::put('/admin/events/{event}', [EventAdminController::class, 'update'])->name('dashboard.admin.events.update');
        Route::delete('/admin/events/{event}', [EventAdminController::class, 'destroy'])->name('dashboard.admin.events.destroy');

        Route::get('/admin/donation-causes/donation-causes', [DonationCauseAdminController::class, 'index'])->name('dashboard.admin.donation-causes.donation-causes');
        Route::get('/admin/donation-causes/create', [DonationCauseAdminController::class, 'create'])->name('dashboard.admin.donation-causes.create');
        Route::post('/admin/donation-causes/donation-causes', [DonationCauseAdminController::class, 'store'])->name('dashboard.admin.donation-causes.store');
        Route::get('/admin/donation-causes/{donationCause}/edit', [DonationCauseAdminController::class, 'edit'])->name('dashboard.admin.donation-causes.edit');
        Route::put('/admin/donation-causes/{donationCause}', [DonationCauseAdminController::class, 'update'])->name('dashboard.admin.donation-causes.update');
        Route::delete('/admin/donation-causes/{donationCause}', [DonationCauseAdminController::class, 'destroy'])->name('dashboard.admin.donation-causes.destroy');

        Route::get('/admin/groups', [GroupAdminController::class, 'index'])->name('dashboard.admin.groups');
        Route::put('/admin/groups/{group}', [GroupAdminController::class, 'update'])->name('dashboard.admin.groups.update');
        Route::delete('/admin/groups/{group}', [GroupAdminController::class, 'destroy'])->name('dashboard.admin.groups.destroy');

        // Complaints (Admin)
        Route::get('/admin/complaints', [ComplaintAdminController::class, 'index'])->name('admin.complaints.index');
        Route::get('/admin/complaints/{complaint}', [ComplaintAdminController::class, 'show'])->name('admin.complaints.show');
        Route::get('/admin/complaints/{complaint}/edit', [ComplaintAdminController::class, 'edit'])->name('admin.complaints.edit');
        Route::put('/admin/complaints/{complaint}', [ComplaintAdminController::class, 'update'])->name('admin.complaints.update');
        Route::delete('/admin/complaints/{complaint}', [ComplaintAdminController::class, 'destroy'])->name('admin.complaints.destroy');
            Route::get('/admin/donation-causes/donation-causes', [DonationCauseAdminController::class, 'index'])->name('dashboard.admin.donation-causes.donation-causes');
            Route::get('/admin/donation-causes/create', [DonationCauseAdminController::class, 'create'])->name('dashboard.admin.donation-causes.create');
            Route::post('/admin/donation-causes/donation-causes', [DonationCauseAdminController::class, 'store'])->name('dashboard.admin.donation-causes.store');
            Route::get('/admin/donation-causes/{donationCause}/edit', [DonationCauseAdminController::class, 'edit'])->name('dashboard.admin.donation-causes.edit');
            Route::put('/admin/donation-causes/{donationCause}', [DonationCauseAdminController::class, 'update'])->name('dashboard.admin.donation-causes.update');
            Route::delete('/admin/donation-causes/{donationCause}', [DonationCauseAdminController::class, 'destroy'])->name('dashboard.admin.donation-causes.destroy'); 
            Route::get('/admin/donation-causes/{donationCause}/donations', [DonationCauseAdminController::class, 'donations'])->name('dashboard.admin.donation-causes.donations');
            Route::delete('/admin/donation-causes/{donationCause}/donations/{donation}', [DonationCauseAdminController::class, 'destroyDonation'])->name('admin.donations.destroy');
            Route::post('/admin/donation-causes/generate-image', [DonationCauseAdminController::class, 'generateImage'])->name('dashboard.admin.donation-causes.generate-image');
            Route::post('/admin/donation-causes/generate-description', [DonationCauseAdminController::class, 'generateDescription'])->name('dashboard.admin.donation-causes.generate-description');
            // Admin: Groups
            Route::get('/admin/groups', [GroupAdminController::class, 'index'])->name('dashboard.admin.groups');
            Route::put('/admin/groups/{group}', [GroupAdminController::class, 'update'])->name('dashboard.admin.groups.update');
            Route::delete('/admin/groups/{group}', [GroupAdminController::class, 'destroy'])->name('dashboard.admin.groups.destroy');
        });
    });


// Render Blade homepage
Route::get('/', function () {
    return view('pages.home');
})->name('home');

// Static content pages via Blade
Route::view('/about', 'pages.about')->name('about');
Route::view('/services', 'pages.services')->name('services');
Route::view('/contact', 'pages.contact')->name('contact');

// Selected pages from template
Route::view('/projects', 'pages.projects')->name('projects');
Route::view('/project', 'pages.project-single')->name('project.single');

Route::get('/donations', fn() => redirect()->route('donation-causes.index'))->name('donations');
Route::view('/donation', 'pages.donation-single')->name('donation.single');

Route::view('/blog', 'pages.blog')->name('blog');
Route::view('/blog-post', 'pages.blog-single')->name('blog.single');

// Removed legacy static event pages to avoid route conflicts with real EventController routes

Route::view('/team', 'pages.team')->name('team');
Route::view('/team-member', 'pages.team-single')->name('team.single');

Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/error', 'pages.error-page')->name('error.page');

// Authentication routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

    // Google OAuth routes
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google-callback', [AuthController::class, 'handleGoogleCallback']);

    // Facebook OAuth routes
    Route::get('/auth/facebook', [AuthController::class, 'redirectToFacebook'])->name('facebook.login');
    Route::get('/auth/facebook-callback', [AuthController::class, 'handleFacebookCallback']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Groups
Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
Route::middleware('auth')->group(function () {
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{slug}/edit', [GroupController::class, 'edit'])->name('groups.edit');
    Route::put('/groups/{slug}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('/groups/{slug}', [GroupController::class, 'destroy'])->name('groups.destroy');
});
Route::get('/groups/{slug}', [GroupController::class, 'show'])->name('groups.show');

// Membership
Route::middleware('auth')->group(function () {
    // Group posts: create
    Route::post('/groups/{slug}/posts', [GroupPostController::class, 'store'])->name('groups.posts.store');

    Route::post('/groups/{slug}/join', [MembershipController::class, 'join'])->name('groups.join');
    Route::post('/groups/{slug}/leave', [MembershipController::class, 'leave'])->name('groups.leave');
    Route::post('/groups/{slug}/requests/{requestId}/approve', [MembershipController::class, 'approve'])->name('groups.requests.approve');
    Route::post('/groups/{slug}/requests/{requestId}/reject', [MembershipController::class, 'reject'])->name('groups.requests.reject');
});

// Events
Route::get('/events', [EventController::class, 'index'])->name('events.index');
// Group posts routes (left intact but separated from Events)
Route::middleware('auth')->group(function () {
    // Group posts: reactions & comments & deletion
    Route::post('/posts/{postId}/react', [GroupPostController::class, 'react'])->name('groups.posts.react');
    Route::post('/posts/{postId}/comment', [GroupPostController::class, 'comment'])->name('groups.posts.comment');
    Route::delete('/posts/{postId}', [GroupPostController::class, 'destroy'])->name('groups.posts.destroy');
});
Route::get('/posts/{postId}/pdf', [GroupPostController::class, 'pdf'])->name('groups.posts.pdf');
Route::get('/posts/{postId}/print', [GroupPostController::class, 'print'])->name('groups.posts.print');

// API tools for groups
Route::middleware('auth')->group(function () {
    Route::post('/api/inspire', [GroupToolsController::class, 'inspireGeneric'])->name('api.inspire');
    Route::post('/api/groups/{slug}/inspire', [GroupToolsController::class, 'inspire'])->name('api.groups.inspire');
    Route::post('/api/moderate', [GroupToolsController::class, 'moderate'])->name('api.moderate');
    Route::post('/api/groups/{slug}/tts', [GroupToolsController::class, 'tts'])->name('api.groups.tts');
    Route::post('/api/groups/{slug}/stt', [GroupToolsController::class, 'stt'])->name('api.groups.stt');
});

// Notifications
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAll'])->name('notifications.readAll');
});

// Donations
Route::resource('donation-causes', DonationCauseController::class);
Route::post('donations', [DonationController::class, 'store'])->name('donations.store')->middleware('auth');

// Events (canonical set)
Route::get('/events/{event}', [EventController::class, 'show'])->whereNumber('event')->name('events.show');
Route::middleware('auth')->group(function () {
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->whereNumber('event')->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->whereNumber('event')->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->whereNumber('event')->name('events.destroy');
    // Event participation
    Route::post('/events/{event}/register', [EventController::class, 'register'])->whereNumber('event')->name('events.register');
    Route::delete('/events/{event}/register', [EventController::class, 'unregister'])->whereNumber('event')->name('events.unregister');
    Route::post('/events/{event}/comment', [EventController::class, 'storeComment'])->whereNumber('event')->name('comments.store');
    Route::delete('/comments/{comment}', [EventController::class, 'destroyComment'])->name('comments.destroy');
});

// Products
Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class)->except(['show']);
    Route::post('products/{product}/comment', [ProductController::class, 'storeComment'])->name('products.comment');
});
Route::view('/product', 'pages.product')->name('product');

// Cart
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
});
// Allow cart content to be fetched via AJAX for guests (uses session_id)
Route::get('/cart/content', [CartController::class, 'content'])->name('cart.content');

// Orders
Route::middleware('auth')->group(function () {
    // Page de paiement (GET)
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    
    // Création de la commande (POST)
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    
    // Historique des commandes de l'utilisateur
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    
    // Affichage d'une commande spécifique
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    
    // Affichage du reçu de commande
    Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
    
    // Téléchargement du reçu en PDF
    Route::get('/orders/{order}/download-receipt', [OrderController::class, 'downloadReceipt'])->name('orders.download-receipt');
    
    // Route d'annulation
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});
// Duplicate comments destroy route removed; defined above in auth group
    Route::get('complaint-types', [ComplaintTypeController::class, 'index'])->name('complaint-types.index');
Route::get('complaint-types/{complaintType}', [ComplaintTypeController::class, 'show'])->name('complaint-types.show');
Route::resource('complaints', ComplaintController::class);
Route::get('/events/create', [EventController::class, 'create'])->name('events.create')->middleware('auth');
Route::post('/events', [EventController::class, 'store'])->name('events.store');
Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
Route::post('/events/{event}/comment', [EventController::class, 'storeComment'])
    ->name('comments.store')->middleware('auth');
// Réactions Like / Dislike
Route::post('/comments/{comment}/react/{type}', [EventController::class, 'reactComment'])
    ->name('comments.react')
    ->middleware('auth');
Route::delete('/comments/{comment}', [EventController::class, 'destroyComment'])
    ->name('comments.destroy')->middleware('auth');
Route::post('/events/{event}/request', [EventController::class, 'requestParticipation'])
    ->name('events.requestParticipation');

Route::post('/events/{event}/approve/{user}', [EventController::class, 'approve'])->name('events.approve');
Route::post('/events/{event}/reject/{user}', [EventController::class, 'reject'])->name('events.reject');
Route::delete('/events/{event}/unregister', [EventController::class, 'unregister'])
    ->name('events.unregister');
    // Paiement
/// Paiement (payant)
// Affiche la page de paiement
// Afficher le formulaire de paiement
// Afficher le formulaire de paiement
Route::get('/events/{event}/payment', [EventController::class, 'showPaymentForm'])
    ->name('events.payment')
    ->middleware('auth');

// Créer la Stripe Checkout Session
Route::post('/events/{event}/payment', [EventController::class, 'processPayment'])
    ->name('events.processPayment')
    ->middleware('auth');

// Redirection après succès
Route::get('/events/{event}/payment-success', [EventController::class, 'paymentSuccess'])
    ->name('events.payment.success')
    ->middleware('auth');


Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot', [ChatbotController::class, 'ask'])->name('chatbot.ask');

Route::get('events/{event}/certificate/{participant}', [EventController::class, 'certificate'])
    ->name('events.certificate')
    ->middleware('auth');


// Participation gratuite / demande


Route::post('/complaints/improve-message', [ComplaintController::class, 'improveMessage']);
Route::middleware(['auth'])->group(function () {
    Route::post('/complaints/improve-message', [ComplaintController::class, 'improveMessage'])
        ->name('complaints.improve-message');
});


Route::get('/admin/complaints/{complaint}/translate', [ComplaintAdminController::class, 'translate'])
    ->name('admin.complaints.translate');