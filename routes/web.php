<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Email Verification Notice
// Route::get('/email/verify', function () {
//     return view('auth.verify-email');
// })->middleware('auth')->name('verification.notice');

// //Email Verification Notice Handler
// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//     $request->fulfill();
 
//     return redirect('/login');
// })->middleware(['auth', 'signed'])->name('verification.verify');

// //Resending Verification Email
// Route::post('/email/verification-notification', function (Request $request) {
//     $request->user()->sendEmailVerificationNotification();
 
//     return back()->with('message', 'Verification link sent!');
// })->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::controller(App\Http\Controllers\UserController::class)->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::get('/login', 'viewLogin')->name('login');
        Route::post('/login', 'login');
        Route::get('/register', 'viewRegister');
        Route::post('/register', 'register');
    });
    Route::post('/logout', 'logout')->middleware('auth');
});

Route::resource('admin-dashboard', App\Http\Controllers\AdminDashboardController::class)->middleware(['auth', 'admin']);

Route::post('/attachment',[App\Http\Controllers\AttachmentController::class,'store']); //Trix file upload

//Route::get('/course', [App\Http\Controllers\PageController::class, 'course']);
Route::resource('course', App\Http\Controllers\CourseController::class)->only(['index','show']);
Route::controller(App\Http\Controllers\CourseController::class)->group(function () {
    Route::get('course/section/{section}', 'learn');
    Route::get('course/quiz/{section}', 'quiz');
    Route::post('course/quiz/{section}', 'submitQuiz');
    Route::get('course/review/{result}', 'result');
    Route::get('course/finish/{course}', 'finish');
})->middleware(['auth']);

Route::controller(App\Http\Controllers\HomeController::class)->group(function () {
    Route::get('/home', 'home')->middleware('auth');
});

Route::controller(App\Http\Controllers\EnrollmentController::class)->group(function () {
    Route::get('enroll/{course}', 'enroll')->middleware('auth');

    //Midtrans Notif Handler
    Route::post('/midtrans/notification',  'notificationHandler');
});
