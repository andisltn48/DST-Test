<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\TransactionController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register/admin',[AuthController::class,'registerAdmin']);
Route::post('/register/user',[AuthController::class,'registerUser']);
Route::post('/login',[AuthController::class,'login']);

Route::group(['middleware' => ['auth:sanctum']], function()
{
    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('/products',[ProductController::class,'getAllProduct']);
    Route::get('/products/{uuid}',[ProductController::class,'productDetail']);

    //transaction
    Route::post('/transactions/{uuid}',[TransactionController::class,'createransaction']);
    Route::get('/transactions',[TransactionController::class,'getAllTransaction']);
    Route::get('/transactions/{uuid}',[TransactionController::class,'getDetailTransaction']);
});

Route::group(['middleware' => ['auth:sanctum','rolecheck:Admin']], function()
{
    Route::post('/products',[ProductController::class,'storeProduct']);
    Route::post('/products/{uuid}',[ProductController::class,'updateProduct']);
    Route::delete('/products/{uuid}',[ProductController::class,'deleteProduct']);
});

Route::get('/not-authenticated',[AuthController::class,'notAuthenticated'])->name('not-authenticated');