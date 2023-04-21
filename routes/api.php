<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Employee\TransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use App\Models\Transaction;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\FuncCall;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'index']);

    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::resource('products', ProductController::class)->only([
            'store', 'update', 'destroy'
        ]);
        Route::resource('supplies', SupplyController::class)->only([
            'index', 'store', 'update', 'destroy'
        ]);
        Route::post('/role', [RoleController::class, 'store']);
        Route::resource('employee', UserController::class)->only([
            'index', 'store', 'update', 'destroy'
        ]);
    });
    Route::middleware(['role:client'])->prefix('client')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/order', [OrderController::class, 'store']);
        Route::resource('profile', ProfileController::class)->only([
            'store', 'update', 'destroy', 'show'
        ]);
    });
    Route::middleware(['role:employee'])->prefix('employee')->group(function () {
        Route::resource('transaction', TransactionController::class)->only([
            'index', 'store', 'update', 'destroy'
        ]);
    });
});
