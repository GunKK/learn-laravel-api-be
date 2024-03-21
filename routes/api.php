<?php

use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\ImportController;
use App\Http\Controllers\api\v1\ProfileController;
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

Route::group(['prefix' => 'v1'], function() {
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group( function () {
        Route::delete('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/test', [AuthController::class, 'test']);
        Route::get('auth/me', [ProfileController::class, 'me']);
        Route::apiResource('users', UserController::class);

        // Admin
        Route::group([
            'prefix' => 'admin',
            'middleware' => ['roles:admin,supervisor']
        ], function() {
            Route::get('test-roles', function() {
                return response()->json('test_roles');
            });

            Route::post('import.teacher', [ImportController::class, 'importTeachers']);
            Route::post('import.student', [ImportController::class, 'importStudents']);
            Route::post('import.subject', [ImportController::class, 'importSubjects']);
        });

        // Teacher
        // Route::group([
        //     'prefix' => 'roles:teacher',
        //     'middleware' => 'teacher'
        // ]. function() {

        // });
        //Student
        // Route::group([
        //     'prefix' => 'roles:student',
        //     'middleware' => 'student'
        // ]. function() {

        // });

    });
});

