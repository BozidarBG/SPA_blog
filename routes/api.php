<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\v1\AdminArticleController;
use App\Http\Controllers\v1\AdminBannedUserController;
use App\Http\Controllers\v1\AdminCommentController;
use App\Http\Controllers\v1\AdminGenreController;
use App\Http\Controllers\v1\AdminUserController;
use App\Http\Controllers\v1\ArticleController;
use App\Http\Controllers\v1\CommentController;
use App\Http\Controllers\v1\ForgotPasswordController;
use App\Http\Controllers\v1\GenreController;
use App\Http\Controllers\v1\LikeController;
use App\Http\Controllers\v1\LoginController;
use App\Http\Controllers\v1\ProfileController;
use App\Http\Controllers\v1\RegisterController;

//routes for all
//must not have api/v1 prefix. without Bearer token
Route::get('/verify-email/{id}/{hash}', [RegisterController::class, 'verifyEmail'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

Route::prefix('v1')->group(function(){
    Route::post('/resend-verification-email', [RegisterController::class, 'resendConfirmEmailAddress'])->middleware(['throttle:6,1'])->name('verification.send');
    Route::post('/send-forgot-password-email', [ForgotPasswordController::class, 'forgotPassword'])->middleware(['throttle:60,1'])->name('password.email');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->middleware(['throttle:6,1'])->name('password.update');

    Route::post('/register', [RegisterController::class, 'register'])->middleware(['guest']);
    Route::post('/login', [LoginController::class, 'login'])->middleware(['guest','throttle:3,1'])->name('login');

    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');
    Route::get('/genres', [GenreController::class, 'index']);
    Route::get('/genres/{id}', [GenreController::class, 'show']);
});

//registered, verified and authenticated users that are not banned
Route::prefix('v1')->middleware(['auth:sanctum', 'is_banned', 'verified'])->group(function(){
    Route::post('/articles', [ArticleController::class, 'store']);
    Route::post('/articles/{slug}', [ArticleController::class, 'update']);
    Route::post('/toggle-like/{slug}', [LikeController::class, 'toggle']);
    Route::post('/delete-articles/{slug}', [ArticleController::class, 'destroy']);


    Route::post('/comments', [CommentController::class, 'store']);
    Route::post('/comments/{id}', [CommentController::class, 'update']);
    Route::post('/delete-comments/{id}', [CommentController::class, 'destroy']);

    Route::get('/my-articles', [ArticleController::class, 'myArticles']);
    Route::get('/my-comments', [CommentController::class, 'myComments']);
    Route::post('/update-avatar', [ProfileController::class, 'updateAvatar']);
    Route::post('/change-password', [ProfileController::class, 'changePassword']);
    Route::post('/delete-avatar', [ProfileController::class, 'deleteAvatar']);
    Route::post('/delete-profile', [ProfileController::class, 'deleteProfile']);

});

//admin routes
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'admin'])->group(function(){
    Route::post('/delete-article/{slug}', [AdminArticleController::class, 'destroy']);
    Route::post('/delete-comment/{id}', [AdminCommentController::class, 'destroy']);
    Route::post('/genres', [AdminGenreController::class, 'store']);
    Route::post('/genres/update/{id}', [AdminGenreController::class, 'update']);
    Route::post('/delete-genre/{id}', [AdminGenreController::class, 'destroy']);
    Route::post('/ban-user', [AdminBannedUserController::class, 'banUser']);
    Route::post('/remove-ban/{id}', [AdminBannedUserController::class, 'removeBan']);
    Route::get('/banned-users', [AdminBannedUserController::class, 'index']);
    Route::get('/users', [AdminUserController::class, 'index']);
});

Route::fallback(function() {
    return response()->json(['errors'=>true, 'data'=>'Not found']);
});


