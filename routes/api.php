<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Đăng ký-Đăng nhập-Đăng xuất
Route::post('/register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// môn học
Route::resource('subjects', SubjectController::class);
Route::delete('subjects', [SubjectController::class, 'destroyAll']);
//vai trò
Route::resource('roles', RoleController::class);
Route::delete('roles', [RoleController::class, 'destroyAll']);
//người dùng
Route::get('/users', [AuthController::class, 'getAllUsers']);
Route::post('/users', [AuthController::class, 'update']);
Route::post('/users', [AuthController::class, 'destroy']);

Route::delete('users', [AuthController::class, 'destroyAll']);

// Route::get('/auth/{provider}', [AuthController::class, 'redirectToProvider'])->name('social.login');
// Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('social.callback');
/* 
Route::middleware('auth:sanctum')->get('/api/logout', function (Request $request) {
    $request->user()->tokens()->delete();
    return response()->json(['message' => 'Đăng xuất thành công'], 200);
});
 */