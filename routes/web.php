<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Transaction\TransactionController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login-process', [AuthController::class, 'login_action'])->name('login.action');
Route::get('order/success', [TransactionController::class, 'orderSuccess'])->name('order.success');
Route::get('order/{id}', [TransactionController::class, 'order'])->name('order');
Route::post('order/{id}', [TransactionController::class, 'orderStore'])->name('order.store');

Route::middleware(['auth', 'auth.session'])->group(function () {
  Route::post('logout', [AuthController::class, 'logout'])->name('logout');
  Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

  Route::get('/recap', [DashboardController::class, 'recap'])->name('dashboard.recap');

  Route::prefix('trx')->group(function () {
    Route::get('create', [TransactionController::class, 'create'])->name('trx.create');
    Route::post('store', [TransactionController::class, 'store'])->name('trx.store');
    Route::post('{id}', [TransactionController::class, 'view'])->name('trx.view');
  });

  Route::prefix('recap')->group(function () {
    Route::get('/', [DashboardController::class, 'recap'])->name('dashboard.recap');
    Route::get('/export', [TransactionController::class, 'export'])->name('recap.export');
  });
});