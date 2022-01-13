<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Verified;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

//require __DIR__.'/auth.php';
//Route::get('/verify-email/{id}/{hash}', function ($id, $hash){
//    $user=\App\Models\User::find($id);
//    abort_if(!$user, 403);
//    abort_if(!hash_equals($hash, sha1($user->getEmailForVerification())), 403);
//
//    if(!$user->hasVerifiedEmail()){
//        $user->markEmailAsVerified();
//        event(new Verified($user));
//    }
//
//    return response()->json(['success'=>true, 'data'=>'Account is verified']);
//
//})->middleware(['signed'])->name('verification.verify');
