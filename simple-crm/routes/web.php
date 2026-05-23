<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChatbotController;

// Authentication Routes (only accessible when not authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout Route (accessible when authenticated)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Root route - redirect to dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Customer Routes
    Route::resource('customers', CustomerController::class);

    // Contact Routes
    Route::resource('contacts', ContactController::class);

    // Deal Routes
    Route::resource('deals', DealController::class);

    // Pipeline Board
    Route::get('/pipeline', [\App\Http\Controllers\PipelineController::class, 'index'])->name('pipeline.index');
    Route::post('/pipeline/move', [\App\Http\Controllers\PipelineController::class, 'moveDeal'])->name('pipeline.move');

    // Activity Routes
    Route::resource('activities', ActivityController::class);
    Route::get('/activities/calendar', [ActivityController::class, 'calendar'])->name('activities.calendar');
    Route::get('/activities/timeline', [ActivityController::class, 'timeline'])->name('activities.timeline');


    // Calendar routes
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::post('/calendar/activities', [CalendarController::class, 'store'])->name('calendar.activities.store');

    // Chatbot API route (for floating widget only)
    Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');
    
    // Download/Export API routes
    Route::get('/api/download/deals', [ChatbotController::class, 'downloadDeals'])->middleware('permission:reports.view')->name('api.download.deals');
    Route::get('/api/download/contacts', [ChatbotController::class, 'downloadContacts'])->middleware('permission:reports.view')->name('api.download.contacts');
    Route::get('/api/download/pipeline', [ChatbotController::class, 'downloadPipeline'])->middleware('permission:reports.view')->name('api.download.pipeline');

});

// Include admin routes
require __DIR__.'/admin.php';

Route::get('/test-assets', function () {
    try {
        return [
            'css' => \Illuminate\Support\Facades\Vite::asset('resources/css/app.css'),
            'js' => \Illuminate\Support\Facades\Vite::asset('resources/js/app.js'),
        ];
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});


