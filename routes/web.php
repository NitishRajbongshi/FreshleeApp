<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\FreshleeMarket\ItemReportController;
use App\Http\Controllers\FreshleeMarket\ProxyOrderController;
use App\Http\Controllers\FreshleeMaster\ItemController;
use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\UtilsController;
use Illuminate\Support\Facades\Route;

// Start of freshlee routes
// user login
Route::get('', [AuthController::class, 'login'])->name('auth.login');
Route::post('', [AuthController::class, 'generateOtp']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verify');
Route::get('logout', [AuthController::class, 'logoutUser'])->name('auth.logout');

// dashboard
Route::group(['prefix' => 'user', 'middleware' => ['auth.user']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/attemptlogin', [UtilsController::class, 'attemptLogin'])->name('attemptLogin');
    Route::post('/change-password', [AuthController::class, 'updatePassword'])->name('auth.update.password');
    Route::get('/profile', [ProfileController::class, 'profile'])->name('admin.profile');
});

// admin routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth.user', 'auth.admin']], function () {
    // admin/master
    Route::get('master/item-details', [ItemController::class, 'index'])->name('admin.master.item');
    Route::get('master/item/create', [ItemController::class, 'create'])->name('admin.master.item.create');
    Route::post('master/item/create', [ItemController::class, 'store']);
    Route::put('master/item/edit', [ItemController::class, 'update'])->name('admin.master.item.update');
    Route::delete('master/items/delete', [ItemController::class, 'destroy'])->name('admin.master.item.destroy');
    Route::get('master/items/image', [ItemController::class, 'getItemImage'])->name('admin.master.item.image');

    // user/order
    Route::get("order/reports", [ItemReportController::class, 'index'])->name('admin.user.order');
    Route::get("order/modify-order", [ItemReportController::class, 'modify'])->name('admin.user.order.modify');
    Route::post("order/update", [ItemReportController::class, 'update'])->name('admin.user.order.update');
    Route::post("order/create", [ItemReportController::class, 'store'])->name('admin.user.order.create');
    Route::delete("order/delete", [ItemReportController::class, 'destroy'])->name('admin.user.order.delete');
    Route::post("order/delivery/update", [ItemReportController::class, 'updateDeliveryStatus'])->name('admin.order.delivery.update');
    Route::post("order/history", [ItemReportController::class, 'history'])->name('admin.order.history');
    Route::post('order/billing', [ItemReportController::class, 'billing'])->name('order.billing');
    Route::post('order/delivered', [ItemReportController::class, 'markAsDelivered'])->name('order.delivered');
    Route::post('order/invoice', [InvoiceController::class, 'generateInvoice'])->name('generate.invoice');

    // Proxy order for users
    Route::get('order/user-list', [ProxyOrderController::class, 'index'])->name('admin.proxy.user.list');
    Route::post('order/customer/address', [ProxyOrderController::class, 'getCustomerAddress'])->name('admin.customer.address');
    Route::get('order/place-order', [ProxyOrderController::class, 'create'])->name('admin.place.order');
    Route::post('order/place-order', [ProxyOrderController::class, 'store']);
});

// End of freshlee routes