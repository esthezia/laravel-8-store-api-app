<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Middleware\CustomAuth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('main');
});

Route::get('/get-products/{product?}', [ApiController::class, 'getProducts']);
Route::get('/get-categories', [ApiController::class, 'getCategories']);
Route::get('/get-total-value', [ApiController::class, 'getTotalValue']);
Route::post('/create-product', [ApiController::class, 'createProduct'])->middleware(CustomAuth::class);
Route::patch('/create-product/{product}', [ApiController::class, 'createProduct'])->middleware(CustomAuth::class);
Route::delete('/delete-product/{product}', [ApiController::class, 'deleteProduct'])->middleware(CustomAuth::class);
