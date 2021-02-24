<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Controllers\UserController;
use App\Http\Controllers\toDelete;

Route::get('home', [RolePermissionController::class, 'index'])->name('home');
Route::post('login', [AuthController::class, 'login']);
Route::post('studentregister', [AuthController::class, 'studentregister']);
Route::post('mentorregister', [AuthController::class, 'mentorregister']);
Route::post('entrepreneurregister', [AuthController::class, 'entrepreneurregister']);
Route::post('universityregister', [AuthController::class, 'universityregister']);
Route::get('Delete/{id}', [toDelete::class, 'toDeleteUser']);

Route::group(
    [
        'middleware' => 'jwt.verify',
        'namespace'  => 'App\Http\Middleware',
    ],
    function ($router) {
        Route::get('getAllUser', [AuthController::class, 'getAllUser'])->middleware('role:University');
        Route::get('user', [AuthController::class, 'user']);
        Route::post('logout', [AuthController::class, 'logout']);
        //refresh token when done gives new token
        Route::post('refresh', [AuthController::class, 'refresh'])->middleware('role:University'); 
    }
);