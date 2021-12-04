<?php

use App\Http\Controllers\AdminAdminController;
use App\Http\Controllers\AdminHomeController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminSupplierController;
use App\Http\Controllers\AdminTenderController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\UserBidController;
use App\Http\Controllers\UserHomeController;
use App\Http\Controllers\UserLoginController;
use App\Http\Controllers\UserTenderController;
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

/**
 * Admin routes
 */
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'handleLogin'])->name('admin.handleLogin');
Route::get('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

Route::name('admin.')->prefix('admin')->group(function() {
    Route::group(['middleware'=>'auth:admin'], function() {
        Route::get('/', [AdminHomeController::class, 'index'])->name('home');
        Route::get('users/data', [AdminUserController::class, 'anyData'])->name('users.data');
        Route::resource('users', AdminUserController::class);

        Route::get('admins/data', [AdminAdminController::class, 'anyData'])->name('admins.data');
        Route::resource('admins', AdminAdminController::class);

        Route::get('suppliers/data', [AdminSupplierController::class, 'anyData'])->name('suppliers.data');
        Route::resource('suppliers', AdminSupplierController::class);

        Route::patch('tenders/storeSuppliers', [AdminTenderController::class, 'storeSuppliers'])->name('tenders.storeSuppliers');
        Route::get('tenders/createSuppliers', [AdminTenderController::class, 'createSuppliers'])->name('tenders.createSuppliers');
        Route::get('tenders/data', [AdminTenderController::class, 'anyData'])->name('tenders.data');
        Route::resource('tenders', AdminTenderController::class);
        //Route::get('tenders/getSuppliers/{id}', [AdminTenderController::class, 'getSuppliers'])->name('tenders.getsuppliers');
        Route::get('tenders/changeStatus/{id}', [AdminTenderController::class, 'changeStatus'])->name('tenders.changeStatus');
        Route::patch('tenders/updateStatus/{id}', [AdminTenderController::class, 'updateStatus'])->name('tenders.updateStatus');
        Route::get('tenders/result/{id}', [AdminTenderController::class, 'result'])->name('tenders.result');
        Route::post('tenders/sendResult/{id}', [AdminTenderController::class, 'sendResult'])->name('tenders.sendResult');
        Route::delete('tenders/destroyResult/{bid_id}', [AdminTenderController::class, 'destroyResult'])->name('tenders.destroyResult');
    });
});

/**
 * User routes
 */
Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('user.login');
Route::post('/login', [UserLoginController::class, 'handleLogin'])->name('user.handleLogin');
Route::get('/logout', [UserLoginController::class, 'logout'])->name('user.logout');

Route::group(['middleware'=>'auth:web'], function() {
    Route::get('/', [UserHomeController::class, 'index'])->name('user.home');

    Route::get('tenders/data', [UserTenderController::class, 'anyData'])->name('user.tenders.data');
    Route::get('tenders/{id}', [UserTenderController::class, 'show'])->name('user.tenders.show');
    Route::get('tenders', [UserTenderController::class, 'index'])->name('user.tenders.index');
    Route::get('tenders/bid/{id}', [UserTenderController::class, 'bid'])->name('user.tenders.bid');

    Route::get('bids/{id}/index', [UserBidController::class, 'index'])->name('user.bids.index');
    Route::post('bids/{id}/create', [UserBidController::class, 'create'])->name('user.bids.create');
    Route::delete('bids/{id}/destroy', [UserBidController::class, 'destroy'])->name('user.bids.destroy');
});

