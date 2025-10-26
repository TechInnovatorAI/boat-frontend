<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JiraWebhookController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

// Language switching
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Contact Us Page
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Blog Pages
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Jira Webhook (for receiving ticket updates)
Route::post('/webhooks/jira', [JiraWebhookController::class, 'handle'])->name('webhook.jira');

// Test webhook endpoint
Route::post('/webhooks/test', function() {
    Log::info('Test webhook received', [
        'payload' => request()->all()
    ]);
    return response()->json(['status' => 'test success']);
});
