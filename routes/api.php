<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\V1\{
    AdminController,
    AuthController,
    ServiceProviderController,
    StoryTellerController,
    ForumPostController,
    ForumPostCommentController,
    ForumPostLikeController,
    BlogPostController,
    BlogCommentController,
    ServiceCategoryController,
    ServiceListingController,
    UserController
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
    Route::controller(AuthController::class)->group(function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
        Route::get('/email/verify/{id}', 'verifyEmail')->name('verification.verify');
    });

    Route::get('/service-category', [ServiceCategoryController::class, 'index']);

    Route::controller(BlogPostController::class)->group(function () {
        Route::get('/blog-posts', 'index');
        Route::get('/blog-posts/{blog_post}', 'show');
    });

    Route::controller(ServiceListingController::class)->group(function () {
        Route::get('/service-listings', 'index');
        Route::get('/service-listings/search', 'search');
    });


    // Protected routes
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/service-providers/{serviceProvider}/service-listings', [ServiceProviderController::class, 'serviceListings']);

        Route::controller(AdminController::class)->prefix('admin')->group(function () {
            Route::get('/', 'index');
        });

        Route::apiResource('service-providers', ServiceProviderController::class);
        Route::apiResource('story-tellers', StoryTellerController::class);
        Route::apiResource('service-listings', ServiceListingController::class)->except(['index']);
        Route::apiResource('service-category', ServiceCategoryController::class)->except(['index']);
        Route::apiResource('users', UserController::class);
        Route::apiResource('forum-posts', ForumPostController::class);
        Route::apiResource('forum-posts.comments', ForumPostCommentController::class);
        Route::apiResource('forum-posts.likes', ForumPostLikeController::class);
        Route::apiResource('blog-posts', BlogPostController::class)->except(['index', 'show']);
        Route::apiResource('blog-posts.comments', BlogCommentController::class);
    });
});

