<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CateogryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TaskController;
use App\Models\ProductRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




//Public Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('/categories', CateogryController::class);
    // No Collision
    Route::get('products/search', [ProductController::class, 'search']);
    Route::resource('/products', ProductController::class);
    Route::resource('/rates', ProductRate::class);
    Route::resource('/favorites', ProductRate::class);
    Route::resource('/carts', ProductRate::class);
});
