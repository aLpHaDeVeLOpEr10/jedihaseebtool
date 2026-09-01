<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Tools
Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
Route::get('/tools/{slug}', [ToolController::class, 'show'])->name('tools.show');
Route::post('/tools/{slug}/process', [ToolController::class, 'process'])
    ->name('tools.process')
    ->middleware('throttle:30,1');

// Categories
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Search
Route::get('/search', [ToolController::class, 'search'])->name('search');

// Static pages
Route::get('/about',   [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/terms',   [HomeController::class, 'terms'])->name('terms');
Route::get('/pages/{page}', [HomeController::class, 'page'])->name('pages.show');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
});

Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

    // Dashboard
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Tools CRUD
    Route::resource('tools', Admin\ToolController::class);
    Route::post('tools/{tool}/toggle-status',   [Admin\ToolController::class, 'toggleStatus'])->name('tools.toggle-status');
    Route::post('tools/{tool}/toggle-featured', [Admin\ToolController::class, 'toggleFeatured'])->name('tools.toggle-featured');
    Route::post('tools/{tool}/generate-blade',  [Admin\ToolController::class, 'generateBlade'])->name('tools.generate-blade');
    Route::post('tools/{id}/restore',           [Admin\ToolController::class, 'restore'])->name('tools.restore');

    // Categories CRUD
    Route::resource('categories', Admin\CategoryController::class);
    Route::post('categories/{category}/toggle-status', [Admin\CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');

    // Settings
    Route::get('settings',              [Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings',             [Admin\SettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/currency',    [Admin\SettingsController::class, 'updateCurrencyRates'])->name('settings.currency');

    // Contacts
    Route::resource('contacts', Admin\ContactController::class)->only(['index', 'show', 'destroy']);
    Route::post('contacts/{contact}/mark-read', [Admin\ContactController::class, 'markRead'])->name('contacts.mark-read');
});
