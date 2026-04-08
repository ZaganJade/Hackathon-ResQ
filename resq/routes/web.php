<?php

use App\Http\Controllers\AIAssistController;
use App\Http\Controllers\ChatHistoryController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // AI Assist Routes
    Route::get('/ai-assist', [AIAssistController::class, 'index'])->name('ai-assist.index');
    Route::post('/ai-assist/chat', [AIAssistController::class, 'chat'])->name('ai-assist.chat');
    Route::get('/ai-assist/history', [AIAssistController::class, 'history'])->name('ai-assist.history');
    Route::get('/ai-assist/conversation/{conversationId}', [AIAssistController::class, 'conversation'])->name('ai-assist.conversation');
    Route::post('/ai-assist/new-conversation', [AIAssistController::class, 'newConversation'])->name('ai-assist.new-conversation');

    // Chat History Routes (Task 6)
    Route::get('/chat-history', [ChatHistoryController::class, 'index'])->name('chat-history.index');
    Route::get('/chat-history/search', [ChatHistoryController::class, 'search'])->name('chat-history.search');
    Route::get('/chat-history/stats', [ChatHistoryController::class, 'stats'])->name('chat-history.stats');
    Route::get('/chat-history/{conversationId}', [ChatHistoryController::class, 'show'])->name('chat-history.show');
    Route::delete('/chat-history/{conversationId}', [ChatHistoryController::class, 'destroy'])->name('chat-history.destroy');
    Route::post('/chat-history/{conversationId}/restore', [ChatHistoryController::class, 'restore'])->name('chat-history.restore');
    Route::get('/chat-history/{conversationId}/export', [ChatHistoryController::class, 'export'])->name('chat-history.export');

    // Disaster Map Routes (Task 7)
    Route::get('/map', [MapController::class, 'index'])->name('map.index');
});

// Map API Routes (public)
Route::get('/api/disasters', [MapController::class, 'getDisasters'])->name('api.disasters.index');
Route::get('/api/disasters/stats', [MapController::class, 'getStats'])->name('api.disasters.stats');
Route::get('/api/disasters/{disaster}', [MapController::class, 'show'])->name('api.disasters.show');
Route::get('/api/geocode', [MapController::class, 'geocode'])->name('api.geocode');

require __DIR__.'/auth.php';

