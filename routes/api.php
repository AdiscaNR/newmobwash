<?php

use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Dashboard\DashboardController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});


// routes/web.php
Route::prefix('client')->group(function () {
  Route::get('search', [ClientController::class, 'search']);
});

Route::get('data', [DashboardController::class, 'data']);
Route::get('earning', [DashboardController::class, 'earning']);
