<?php

use App\Http\Controllers\Auth\AdminRegistrationController;
use App\Http\Controllers\Auth\CompanyRegistrationController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\SurveyController;
use App\Http\Controllers\Web\SurveyMeasureController;
use App\Http\Controllers\Web\SurveyResultController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/home/{questionId?}', [SurveyResultController::class, 'show'])->name('home'); // ->middleware(['auth', 'verified']);

Route::get('/forgot-password', [PasswordResetLinkController::class, 'showLinkRequestForm'])
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'sendResetLinkEmail'])
    ->name('password.email');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/survey/thanks', function () {
    return view('survey.thanks');
})->name('survey.thanks');

Route::get('survey/{survey}', [SurveyController::class, 'index']);

Route::post('/survey/{survey}/store', [SurveyController::class, 'store'])->name('survey.store');

// web.php でのルート設定（questionIdを受け取る）
Route::get('/measures/create/{questionId}', [SurveyMeasureController::class, 'create'])->name('measures.create');
// チャットメッセージ送信のルート
Route::post('/measures/create/{questionId}/chat', [SurveyMeasureController::class, 'send'])->name('measures.send');

Route::post('/measures/store', [SurveyMeasureController::class, 'store'])->name('measures.store');

Route::prefix('company/register')->name('company.register.')->group(function () {
    Route::get('/step1', [CompanyRegistrationController::class, 'showStep1'])->name('step1');
    Route::post('/step1', [CompanyRegistrationController::class, 'storeStep1'])->name('step1.store');

    Route::get('/step2', [CompanyRegistrationController::class, 'showStep2'])->name('step2');
    Route::post('/step2', [CompanyRegistrationController::class, 'storeStep2'])->name('step2.store');

    Route::get('/step3', [CompanyRegistrationController::class, 'showStep3'])->name('step3');
    Route::post('/step3', [CompanyRegistrationController::class, 'submitConfirm'])->name('step3.submit');

    Route::get('/step4', [CompanyRegistrationController::class, 'showStep4'])->name('step4');
});

Route::prefix('admin/register')->name('admin.register.')->group(function () {
    Route::get('/step1', [AdminRegistrationController::class, 'showStep1'])->name('step1');
    Route::post('/step1', [AdminRegistrationController::class, 'storeStep1'])->name('step1.store');

    Route::get('/step2', [AdminRegistrationController::class, 'showStep2'])->name('step2');
    Route::post('/step2', [AdminRegistrationController::class, 'storeStep2'])->name('step2.store');

    Route::get('/step3', [AdminRegistrationController::class, 'showStep3'])->name('step3');
    Route::post('/step3', [AdminRegistrationController::class, 'submitConfirm'])->name('step3.submit');

    Route::get('/complete', [AdminRegistrationController::class, 'showStep4'])->name('step4');
});

Route::get('/measures', [SurveyMeasureController::class, 'index'])->name('measures.index');

Route::get('/measures/{id}', [SurveyMeasureController::class, 'show'])->name('measures.show');

Route::post('/measures/{id}/{taskId}', [SurveyMeasureController::class, 'update'])->name('measures.update');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

Route::middleware(['clear.chat.history'])->group(function () {
    Route::get('/measures/create/{questionId}', [SurveyMeasureController::class, 'create'])->name('measures.create');
});

require __DIR__.'/auth.php';
