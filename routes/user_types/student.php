<?php

use App\Http\Controllers\API\Student\Account\AccountController;
use App\Http\Controllers\API\Student\Announcement\AnnouncementController;
use App\Http\Controllers\API\Student\ClassSchedule\ClassScheduleController;
use App\Http\Controllers\API\Student\Exam\ExamController;
use App\Http\Controllers\API\Student\ExamType\ExamTypeController;
use App\Http\Controllers\API\Student\Location\LocationController;
use App\Http\Controllers\API\Student\Note\NoteController;
use App\Http\Controllers\API\Student\QuestionCategory\QuestionCategoryController;
use App\Http\Controllers\API\Student\StaticPage\StaticPageController;
use App\Http\Controllers\API\Student\Subscription\SubscriptionController;
use App\Http\Controllers\API\Student\Subscription\SubscriptionPlanController;
use App\Http\Controllers\API\Student\Support\SupportController;

Route::prefix('accounts')->group(function () {
    Route::get('/', [AccountController::class, 'show']);
    Route::put('/', [AccountController::class, 'update']);
    Route::put('/update-password', [AccountController::class, 'passwordUpdate']);
    Route::delete('/', [AccountController::class, 'destroy']);
});

Route::post('exams/{exam}/answer', [ExamController::class, 'storeAnswer']);
Route::get('exams/results', [ExamController::class, 'getExamResultAll']);
Route::get('exams/results/{exam}', [ExamController::class, 'getExamResult']);
Route::apiResource('exams', ExamController::class)->only(['index', 'store', 'destroy']);
Route::get('exam-types', [ExamTypeController::class, 'index']);

Route::apiResource('question-categories', QuestionCategoryController::class)->only(['index']);
Route::apiResource('supports', SupportController::class)->only(['index', 'store', 'destroy']);
Route::apiResource('notes', NoteController::class);
Route::apiResource('static-pages', StaticPageController::class)->only(['index', 'show']);
Route::apiResource('class-schedules', ClassScheduleController::class);
Route::apiResource('announcements', AnnouncementController::class)->only(['index', 'show']);
Route::get('countries', [LocationController::class, 'getCountry'])->name('country.list');
Route::get('cities/{countryId?}', [LocationController::class, 'getCity'])->name('city.list');
Route::get('states/{cityId?}', [LocationController::class, 'getState'])->name('state.list');

Route::get('subscription-plans', [SubscriptionPlanController::class, 'index']);

Route::prefix('subscriptions')->group(function () {
    Route::get('/', [SubscriptionController::class, 'index']);
    Route::post('/', [SubscriptionController::class, 'store']);
    Route::get('/{subscription}', [SubscriptionController::class, 'show']);
    Route::put('/{subscription}/cancel', [SubscriptionController::class, 'cancel']);
    Route::put('/{subscription}/upgrade', [SubscriptionController::class, 'upgrade']);
    Route::get('/{subscription}/invoice', [SubscriptionController::class, 'invoice']);
});

