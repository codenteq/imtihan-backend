<?php

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
