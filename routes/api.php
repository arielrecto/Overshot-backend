<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Employee\OrderController as EmployeeOrderController;
use App\Http\Controllers\Employee\TransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use App\Models\Transaction;
use Database\Factories\ProductFactory;
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

    Route::middleware(['role:admin'])->prefix('admin')->group(function () {

        Route::prefix('products')->group(function () {
            Route::get('otherinfo', [ProductController::class, 'otherinfo']);
        });

        Route::resource('products', ProductController::class)->only([
            'store', 'update', 'destroy', 'index'
        ]);
        Route::resource('supplies', SupplyController::class)->only([
            'index', 'store', 'update', 'destroy'
        ]);
        Route::post('/role', [RoleController::class, 'store']);
        Route::resource('employee', UserController::class)->only([
            'index', 'store', 'update', 'destroy'
        ]);
        Route::resource('transaction', AdminTransactionController::class)->only([
            'index', 'update', 'destroy'
        ]);

        Route::resource('category', CategoryController::class)->only([
            'index', 'store', 'update', 'destroy'
        ]);
    });
    Route::middleware(['role:client'])->prefix('client')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::resource('/orders', OrderController::class)->only(['index', 'store']);
        Route::resource('profile', ProfileController::class)->only([
            'store', 'update', 'destroy', 'show'
        ]);
    });
    Route::middleware(['role:employee'])->prefix('employee')->group(function () {
        Route::resource('transaction', TransactionController::class)->only([
            'index', 'update', 'destroy'
        ]);
        Route::prefix('transaction')->group(function () {
            Route::post('/order', [TransactionController::class, 'order']);
            Route::post('/pos', [TransactionController::class, 'pointOfSale']);
        });
        Route::resource('orders', EmployeeOrderController::class)->only([
            'index', 'show'
        ]);
    });
});
