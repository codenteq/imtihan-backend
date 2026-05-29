<?php

use App\Http\Controllers\API\Admin\Account\AccountController;
use App\Http\Controllers\API\Admin\Announcement\AnnouncementController;
use App\Http\Controllers\API\Admin\Condition\ConditionController;
use App\Http\Controllers\API\Admin\ExamType\ExamTypeController;
use App\Http\Controllers\API\Admin\Language\LanguageController;
use App\Http\Controllers\API\Admin\Lesson\LessonController;
use App\Http\Controllers\API\Admin\Payment\PaymentCouponController;
use App\Http\Controllers\API\Admin\Payment\PaymentMethodController;
use App\Http\Controllers\API\Admin\Payment\PaymentSettingController;
use App\Http\Controllers\API\Admin\Question\QuestionCatergoryController;
use App\Http\Controllers\API\Admin\Question\QuestionController;
use App\Http\Controllers\API\Admin\StaticPage\StaticPageController;
use App\Http\Controllers\API\Admin\Subscription\SubscriptionController;
use App\Http\Controllers\API\Admin\Subscription\SubscriptionPlanController;
use App\Http\Controllers\API\Admin\Subscription\SubscriptionProductController;
use App\Http\Controllers\API\Admin\Support\SupportController;
use App\Http\Controllers\API\Admin\User\UserController;

Route::apiResource('users', UserController::class);

Route::prefix('accounts')->group(function () {
    Route::get('/', [AccountController::class, 'show']);
    Route::put('/', [AccountController::class, 'update']);
    Route::put('/update-password', [AccountController::class, 'passwordUpdate']);
    Route::delete('/', [AccountController::class, 'destroy']);
});

Route::apiResource('languages', LanguageController::class);
Route::apiResource('lessons', LessonController::class);
Route::apiResource('static-pages', StaticPageController::class);
Route::apiResource('announcements', AnnouncementController::class);
Route::apiResource('supports', SupportController::class)->only(['index', 'show', 'update', 'destroy']);

Route::prefix('payment')->group(function () {
    Route::apiResource('coupons', PaymentCouponController::class);
    Route::apiResource('methods', PaymentMethodController::class);
    Route::apiResource('settings', PaymentSettingController::class);
});

Route::prefix('condition')->group(function () {
    Route::apiResource('conditions', ConditionController::class);
});

Route::get('question/categories/tree', [QuestionCatergoryController::class, 'getTreeCategories']);

Route::apiResources([
    'questions' => QuestionController::class,
    'question/categories' => QuestionCatergoryController::class,
]);

Route::apiResource('exam-types', ExamTypeController::class);
Route::get('exam-type-categories/{exam_type}', [ExamTypeController::class, 'getCategories']);

Route::prefix('subscription')->group(function () {
    Route::apiResource('products', SubscriptionProductController::class);
    Route::get('products/{productReferenceCode}/plans', [SubscriptionPlanController::class, 'index']);
    Route::apiResource('plans', SubscriptionPlanController::class)->except(['index']);
    Route::get('subscriptions', [SubscriptionController::class, 'index']);
    Route::get('subscriptions/{subscription}', [SubscriptionController::class, 'show']);
    Route::put('subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel']);
});

