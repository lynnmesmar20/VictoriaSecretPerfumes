<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\http\Controllers\UserController;
use App\http\Controllers\CartController;
use App\http\Controllers\ProtectedController;
use App\http\Controllers\GrammarController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('products',[ProductController::class,'getAllProducts']);

Route::delete('products/{id}', [ProductController::class, 'deleteProduct']);

Route::post('addproducts',[ProductController::class,'addProduct']);

Route::post('login',[UserController::class,'login']);



Route::post('ecommerce', [CartController::class, 'getCarts']);
Route::post('add', [CartController::class, 'addToCart']);

Route::delete('ecommerce/{userId}/{productId}', [CartController::class, 'deleteCart']);

Route::get('grammars',[GrammarController::class,'getGrammar']);