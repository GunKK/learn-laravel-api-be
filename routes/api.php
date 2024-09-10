<?php

use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\ImportController;
use App\Http\Controllers\api\v1\ProfileController;
use App\Http\Controllers\api\v1\ReportController;
use App\Http\Controllers\api\v1\StudentController;
use App\Http\Controllers\api\v1\TeacherController;
use App\Http\Controllers\api\v1\UserController;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'v1'], function () {
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::delete('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/test', [AuthController::class, 'test']);
        Route::get('auth/me', [ProfileController::class, 'me']);
        Route::post('auth/avatar', [ProfileController::class, 'storeAvatar']);
        Route::apiResource('users', UserController::class);

        // Admin
        Route::group([
            'prefix' => 'admin',
            'middleware' => ['roles:admin,supervisor'],
        ], function () {
            Route::get('test-roles', function () {
                return response()->json('test_roles');
            });

            Route::post('import.teacher', [ImportController::class, 'importTeachers']);
            Route::post('import.student', [ImportController::class, 'importStudents']);
            Route::post('import.subject', [ImportController::class, 'importSubjects']);
        });

        // Teacher
        Route::group([
            'prefix' => 'teacher',
            'middleware' => 'roles:teacher',
        ], function () {
            Route::post('store.teacher_to_subject', [TeacherController::class, 'createTeacherToSubject']);
            Route::post('getAllSubjects', [TeacherController::class, 'getSubjects']);
            Route::put('report.setMark/{id}', [TeacherController::class, 'setMarkReport']);
        });
        //Student
        Route::group([
            'prefix' => 'student',
            'middleware' => 'roles:student',
        ], function () {
            Route::post('studentInfo.store', [StudentController::class, 'store']);
            Route::put('studentInfo.update', [StudentController::class, 'update']);
            Route::post('report.store', [StudentController::class, 'storeReport']);
        });

        // Report
        Route::group([
            'prefix' => 'report',
        ], function () {
            Route::get('view/{id}', [ReportController::class, 'viewReport']);
            Route::get('download/{id}', [ReportController::class, 'downloadReport']);
        });
    });
});
