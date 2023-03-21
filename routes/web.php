<?php

use Illuminate\Support\Facades\Route;

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

Route::get('init-request','App\Http\Controllers\PayWithCard@initRequest');
Route::get('init-own-form','App\Http\Controllers\PayWithCard@initWithOwnForm');
Route::get('pay-with-token','App\Http\Controllers\PayWithToken@payWithToken');

Route::get('refund','App\Http\Controllers\PaymentRefund@refund');
