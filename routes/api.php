<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\V1\{
    AuthController,
    ServiceProviderController,
    StoryTellerController,
    ForumPostController,
    ForumPostCommentController,
    ForumPostLikeController,
    BlogPostController,
    BlogCommentController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    // Auth routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::apiResource('service-providers', ServiceProviderController::class);
        Route::apiResource('story-tellers', StoryTellerController::class);
        Route::apiResource('forum-posts', ForumPostController::class);
        Route::apiResource('forum-posts.comments', ForumPostCommentController::class);
        Route::apiResource('forum-posts.likes', ForumPostLikeController::class);
        Route::apiResource('blog-posts', BlogPostController::class);
        Route::apiResource('blog-posts.comments', BlogCommentController::class);
    });
});

