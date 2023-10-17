<?php

use App\Http\Controllers\API\Admin\Account\AccountController;
use App\Http\Controllers\API\Admin\Announcement\AnnouncementController;
use App\Http\Controllers\API\Admin\Condition\ConditionCategoryController;
use App\Http\Controllers\API\Admin\Condition\ConditionController;
use App\Http\Controllers\API\Admin\Language\LanguageController;
use App\Http\Controllers\API\Admin\Lesson\LessonController;
use App\Http\Controllers\API\Admin\Payment\PaymentCouponController;
use App\Http\Controllers\API\Admin\Payment\PaymentMethodController;
use App\Http\Controllers\API\Admin\Payment\PaymentSettingController;
use App\Http\Controllers\API\Admin\Question\QuestionCatergoryController;
use App\Http\Controllers\API\Admin\Question\QuestionController;
use App\Http\Controllers\API\Admin\Support\SupportController;
use App\Http\Controllers\API\Student\ClassSchedule\ClassScheduleController;
use App\Http\Controllers\API\Student\Exam\ExamController;
use App\Http\Controllers\API\Student\Location\LocationController;
use App\Http\Controllers\API\Student\Note\NoteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/user', function () {
        return request()->user();
    });

    Route::prefix('admin')->group(function () {
        Route::apiResource('accounts', AccountController::class);
        Route::apiResource('languages', LanguageController::class);
        Route::apiResource('lessons', LessonController::class);
        Route::apiResource('announcements', AnnouncementController::class);
        Route::apiResource('supports', SupportController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::prefix('payment')->group(function () {
            Route::apiResource('coupons', PaymentCouponController::class);
            Route::apiResource('methods', PaymentMethodController::class);
            Route::apiResource('settings', PaymentSettingController::class);
        });
        Route::prefix('condition')->group(function () {
            Route::apiResource('conditions', ConditionController::class);
            Route::apiResource('categories', ConditionCategoryController::class);
        });
        Route::apiResources([
            'questions' => QuestionController::class,
            'question/categories' => QuestionCatergoryController::class,
        ]);
    });

    Route::prefix('student')->group(function () {
        Route::apiResource('accounts', \App\Http\Controllers\API\Student\Account\AccountController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::apiResource('exams', ExamController::class)->only(['index', 'store', 'storeAnswer']);
        Route::apiResource('supports', \App\Http\Controllers\API\Student\Support\SupportController::class)->only(['index', 'store', 'destroy']);
        Route::apiResource('notes', NoteController::class);
        Route::apiResource('class-schedules', ClassScheduleController::class);
        Route::apiResource('announcements', \App\Http\Controllers\API\Student\Announcement\AnnouncementController::class)->only(['index', 'show']);
        Route::get('countries', [LocationController::class, 'getCountry'])->name('country.list');
        Route::get('cities/{countryId?}', [LocationController::class, 'getCity'])->name('city.list');
        Route::get('states/{cityId?}', [LocationController::class, 'getState'])->name('state.list');
    });
});
