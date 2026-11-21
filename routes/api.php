<?php

use App\Http\Controllers\AccessCodeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\RsvpController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\WeddingSettingController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/wedding', [WeddingSettingController::class, 'index']);
Route::post('/rsvp', [RsvpController::class, 'store']);
Route::get('/stream/verify/{token}', [RsvpController::class, 'verify']);
Route::get('/chat', [ChatController::class, 'index']);
Route::post('/chat', [ChatController::class, 'store']);
Route::post('/chat/react', [ChatController::class, 'react']);
Route::get('/testimonials', [TestimonialController::class, 'index']);
Route::post('/testimonials', [TestimonialController::class, 'store']);
Route::post('/testimonials/video', [TestimonialController::class, 'storeVideo']);
Route::post('/access-code/scan', [AccessCodeController::class, 'scan']);

// Admin (protect with sanctum in production)
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/rsvps', [AdminController::class, 'rsvps']);
    Route::get('/access-codes', [AccessCodeController::class, 'index']);
    Route::post('/access-codes/create', [AccessCodeController::class, 'create']);
    Route::post('/access-codes/{rsvp}/reset', [AccessCodeController::class, 'reset']);
    Route::get('/donations', [DonationController::class, 'index']);
    Route::put('/wedding', [WeddingSettingController::class, 'update']);
    Route::post('/wedding/story-video', [WeddingSettingController::class, 'uploadStoryVideo']);
    Route::post('/wedding/gallery-video', [WeddingSettingController::class, 'uploadGalleryVideo']);
    Route::delete('/wedding/gallery-video', [WeddingSettingController::class, 'deleteGalleryVideo']);
    Route::post('/wedding/gallery-image', [WeddingSettingController::class, 'uploadGalleryImage']);
    Route::delete('/wedding/gallery-image', [WeddingSettingController::class, 'deleteGalleryImage']);
    Route::delete('/chat/{chat}', [ChatController::class, 'destroy']);
    Route::get('/testimonials', [TestimonialController::class, 'all']);
    Route::patch('/testimonials/{testimonial}/approve', [TestimonialController::class, 'approve']);
    Route::delete('/testimonials/{testimonial}', [TestimonialController::class, 'destroy']);
});
