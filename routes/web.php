<?php

use App\Http\Controllers\AdminAdminController;
use App\Http\Controllers\AdminHomeController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminMaterialController;
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
Route::get('/admin/forgot-password', [AdminLoginController::class, 'showForgotPasswordForm'])->name('admin.forgot.password.get');
Route::post('/admin/forgot-password', [AdminLoginController::class, 'submitForgotPasswordForm'])->name('admin.forgot.password.post');
Route::get('/admin/reset-password/{token}', [AdminLoginController::class, 'showResetPasswordForm'])->name('admin.reset.password.get');
Route::post('/admin/reset-password', [AdminLoginController::class, 'submitResetPasswordForm'])->name('admin.reset.password.post');


Route::name('admin.')->prefix('admin')->group(function() {
    Route::group(['middleware'=>'auth:admin'], function() {
        Route::get('/', [AdminHomeController::class, 'index'])->name('home');
        Route::get('/profile', [AdminHomeController::class, 'profile'])->name('profile');
        Route::get('users/data', [AdminUserController::class, 'anyData'])->name('users.data');
        Route::resource('users', AdminUserController::class);
        Route::post('users/import', [AdminUserController::class, 'import'])->name('users.import');

        Route::get('admins/data', [AdminAdminController::class, 'anyData'])->name('admins.data');
        Route::resource('admins', AdminAdminController::class);

        Route::post('suppliers/import', [AdminSupplierController::class, 'import'])->name('suppliers.import');
        Route::get('suppliers/bidData/{supplier_id}', [AdminSupplierController::class, 'bidData'])->name('suppliers.bidData');
        Route::get('suppliers/data', [AdminSupplierController::class, 'anyData'])->name('suppliers.data');
        Route::resource('suppliers', AdminSupplierController::class);

        Route::get('materials/data', [AdminMaterialController::class, 'anyData'])->name('materials.data');
        Route::post('materials/import', [AdminMaterialController::class, 'import'])->name('materials.import');
        Route::resource('materials', AdminMaterialController::class);

        Route::patch('tenders/storeQuantityAndDeliveryTimes', [AdminTenderController::class, 'storeQuantityAndDeliveryTimes'])->name('tenders.storeQuantityAndDeliveryTimes');
        Route::get('tenders/createQuantityAndDeliveryTimes', [AdminTenderController::class, 'createQuantityAndDeliveryTimes'])->name('tenders.createQuantityAndDeliveryTimes');
        Route::get('tenders/editQuantityAndDeliveryTimes', [AdminTenderController::class, 'editQuantityAndDeliveryTimes'])->name('tenders.editQuantityAndDeliveryTimes');
        Route::patch('tenders/updateQuantityAndDeliveryTimes', [AdminTenderController::class, 'updateQuantityAndDeliveryTimes'])->name('tenders.updateQuantityAndDeliveryTimes');
        Route::patch('tenders/storeSuppliers', [AdminTenderController::class, 'storeSuppliers'])->name('tenders.storeSuppliers');
        Route::get('tenders/createSuppliers', [AdminTenderController::class, 'createSuppliers'])->name('tenders.createSuppliers');
        Route::get('tenders/data', [AdminTenderController::class, 'anyData'])->name('tenders.data');
        Route::resource('tenders', AdminTenderController::class);
        //Route::get('tenders/getSuppliers/{id}', [AdminTenderController::class, 'getSuppliers'])->name('tenders.getsuppliers');
        Route::get('tenders/changeStatus/{id}', [AdminTenderController::class, 'changeStatus'])->name('tenders.changeStatus');
        Route::patch('tenders/updateStatus/{id}', [AdminTenderController::class, 'updateStatus'])->name('tenders.updateStatus');
        Route::get('tenders/createResult/{id}', [AdminTenderController::class, 'createResult'])->name('tenders.createResult');
        Route::post('tenders/storeResult/{id}', [AdminTenderController::class, 'storeResult'])->name('tenders.storeResult');
        Route::delete('tenders/destroyResult/{bid_id}', [AdminTenderController::class, 'destroyResult'])->name('tenders.destroyResult');
        Route::post('tenders/createPropose/{id}', [AdminTenderController::class, 'createPropose'])->name('tenders.create.propose');
        Route::delete('tenders/destroyPropose/{id}', [AdminTenderController::class, 'destroyPropose'])->name('tenders.destroy.propose');
    });
});

/**
 * User routes
 */
Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('user.login');
Route::post('/login', [UserLoginController::class, 'handleLogin'])->name('user.handleLogin');
Route::get('/logout', [UserLoginController::class, 'logout'])->name('user.logout');
Route::get('/forgot-password', [UserLoginController::class, 'showForgotPasswordForm'])->name('user.forgot.password.get');
Route::post('/forgot-password', [UserLoginController::class, 'submitForgotPasswordForm'])->name('user.forgot.password.post');
Route::get('/reset-password/{token}', [UserLoginController::class, 'showResetPasswordForm'])->name('user.reset.password.get');
Route::post('/reset-password', [UserLoginController::class, 'submitResetPasswordForm'])->name('user.reset.password.post');

Route::group(['middleware'=>'auth:web'], function() {
    Route::get('/', [UserHomeController::class, 'index'])->name('user.home');
    Route::get('/profile', [UserHomeController::class, 'profile'])->name('user.profile');
    Route::get('/change-password', [UserHomeController::class, 'showChangePasswordForm'])->name('user.change.password.get');
    Route::post('/change-password', [UserHomeController::class, 'submitChangePasswordForm'])->name('user.change.password.post');

    Route::get('tenders/data', [UserTenderController::class, 'anyData'])->name('user.tenders.data');
    Route::get('tenders/{id}', [UserTenderController::class, 'show'])->name('user.tenders.show');
    Route::get('tenders', [UserTenderController::class, 'index'])->name('user.tenders.index');
    Route::get('tenders/bid/{id}', [UserTenderController::class, 'bid'])->name('user.tenders.bid');

    Route::get('bids/{id}/index', [UserBidController::class, 'index'])->name('user.bids.index');
    Route::post('bids/{id}/create', [UserBidController::class, 'create'])->name('user.bids.create');
    Route::get('bids/{id}/edit', [UserBidController::class, 'edit'])->name('user.bids.edit');
    Route::patch('bids/{id}/update', [UserBidController::class, 'update'])->name('user.bids.update');
    Route::delete('bids/{id}/destroy', [UserBidController::class, 'destroy'])->name('user.bids.destroy');
    Route::get('bids/data', [UserBidController::class, 'anyData'])->name('user.bids.data');
});

