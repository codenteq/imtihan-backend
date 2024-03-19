<?php

use App\Http\Controllers\API\Admin\Account\AccountController;
use App\Http\Controllers\API\Admin\Announcement\AnnouncementController;
use App\Http\Controllers\API\Admin\Condition\ConditionController;
use App\Http\Controllers\API\Admin\Language\LanguageController;
use App\Http\Controllers\API\Admin\Lesson\LessonController;
use App\Http\Controllers\API\Admin\Payment\PaymentCouponController;
use App\Http\Controllers\API\Admin\Payment\PaymentMethodController;
use App\Http\Controllers\API\Admin\Payment\PaymentSettingController;
use App\Http\Controllers\API\Admin\Question\QuestionCatergoryController;
use App\Http\Controllers\API\Admin\Question\QuestionController;
use App\Http\Controllers\API\Admin\StaticPage\StaticPageController;
use App\Http\Controllers\API\Admin\Support\SupportController;
use App\Http\Controllers\API\Admin\User\UserController;
use App\Http\Controllers\API\Student\ClassSchedule\ClassScheduleController;
use App\Http\Controllers\API\Student\Exam\ExamController;
use App\Http\Controllers\API\Student\ExamType\ExamTypeController;
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

    Route::prefix('admin')
        ->middleware(['auth.admin', 'auth.is.active'])
        ->group(base_path('routes/user_types/admin.php'));

    Route::prefix('student')
        ->middleware(['auth.student', 'auth.is.active'])
        ->group(base_path('routes/user_types/student.php'));
});
