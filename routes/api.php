<?php

use App\Http\Controllers\AcquisitionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\WorkstreamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

Route::get('/positions', [WorkstreamController::class, 'getPositions']);

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/locations', [LocationController::class, 'index']); // Read all

Route::get('/events', [EventController::class, 'index']);

Route::post('/feedback', [FeedbackController::class, 'store']); // Create feedback
Route::get('/feedback', [FeedbackController::class, 'index']); // Read published feedback

Route::get('/jobs', [JobController::class, 'index']);


Route::get('/milestones', [MilestoneController::class, 'index']);

Route::get('/media', [MediaController::class, 'index']);

Route::get('/contacts', [ContactController::class, 'index']); // Get all contacts
Route::post('/contacts', [ContactController::class, 'store']); // Create a new contact


Route::get('/acquisitions', [AcquisitionController::class, 'index']);
Route::post('/acquisitions', [AcquisitionController::class, 'store']);


Route::middleware('auth:sanctum')->group(function () {


    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/locations', [LocationController::class, 'store']); // Create
Route::get('/locations/{id}', [LocationController::class, 'show']); // Read single
Route::put('/locations/{id}', [LocationController::class, 'update']); // Update
Route::delete('/locations/{id}', [LocationController::class, 'destroy']); // Delete

Route::post('/events', [EventController::class, 'store']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::put('/events/{id}', [EventController::class, 'update']);
Route::delete('/events/{id}', [EventController::class, 'destroy']);

Route::get('/feedback/admin', [FeedbackController::class, 'adminIndex']); // Read all feedback
Route::put('/feedback/{id}', [FeedbackController::class, 'update']); // Update feedback
Route::delete('/feedback/{id}', [FeedbackController::class, 'destroy']); // Delete feedback


Route::post('/jobs', [JobController::class, 'store']);
Route::get('/jobs/{id}', [JobController::class, 'show']);
Route::put('/jobs/{id}', [JobController::class, 'update']);
Route::delete('/jobs/{id}', [JobController::class, 'destroy']);


Route::post('/milestones', [MilestoneController::class, 'store']);
Route::get('/milestones/{id}', [MilestoneController::class, 'show']);
Route::put('/milestones/{id}', [MilestoneController::class, 'update']);
Route::delete('/milestones/{id}', [MilestoneController::class, 'destroy']);

Route::get('/team-members', [TeamMemberController::class, 'index']);
Route::post('/team-members', [TeamMemberController::class, 'store']);
Route::get('/team-members/{id}', [TeamMemberController::class, 'show']);
Route::put('/team-members/{id}', [TeamMemberController::class, 'update']);
Route::delete('/team-members/{id}', [TeamMemberController::class, 'destroy']);


Route::post('/media', [MediaController::class, 'store']);
Route::get('/media/{id}', [MediaController::class, 'show']);
Route::post('/update/media/{id}', [MediaController::class, 'update']);
Route::delete('/media/{id}', [MediaController::class, 'destroy']);


Route::put('/acquisitions/{id}', [AcquisitionController::class, 'update']);
Route::delete('/acquisitions/{id}', [AcquisitionController::class, 'destroy']);
Route::get('/acquisitions/{id}', [AcquisitionController::class, 'show']);

Route::put('/contacts/{id}', [ContactController::class, 'update']); // Update a specific contact
Route::delete('/contacts/{id}', [ContactController::class, 'destroy']); // Delete a specific contact
Route::get('/contacts/{id}', [ContactController::class, 'show']); // Get a specific contact

Route::prefix('users')->group(function () {
    Route::get('/', [ManageUserController::class, 'index']); // List users
    Route::post('/', [ManageUserController::class, 'store']); // Create user
    Route::get('/{user}', [ManageUserController::class, 'show']); // Show specific user
    Route::post('/{user}', [ManageUserController::class, 'update']); // Update user
    Route::delete('/{user}', [ManageUserController::class, 'destroy']); // Delete user
});
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);

});
    Route::get('/settings', [SettingController::class, 'index']);
    Route::get('/settings/{id}', [SettingController::class, 'view']);
    Route::put('/settings/{id}', [SettingController::class, 'update']);

});


