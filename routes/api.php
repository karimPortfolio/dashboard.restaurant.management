<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\EmployeePositions;
use App\Http\Controllers\Api\V1\EmployeeStatus;
use App\Http\Controllers\Api\V1\ItemController;
use App\Http\Controllers\Api\V1\SupplierController;
use Illuminate\Http\Request;
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


Route::prefix('v1')
    ->group(function () {

        // Route::post('login', [AuthController::class, 'login'])->name('api.login');
        // Route::post('register', [AuthController::class, 'register'])->name('api.register');
        // Route::post('logout', [AuthController::class, 'logout'])->name('api.logout');

        Route::middleware('auth:sanctum')->group(function () {

            Route::get('me', [AuthController::class, 'me'])->name('api.me');


            Route::apiResource('categories', CategoryController::class);
            Route::apiResource('employees', EmployeeController::class);
            Route::get('employee-positions', EmployeePositions::class);
            Route::get('employee-status', EmployeeStatus::class);
            Route::apiResource('suppliers', SupplierController::class);
            Route::apiResource('items', ItemController::class);
        });
    });
