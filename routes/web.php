<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

    // User
    Route::prefix('user')->group(function () {
        Route::get('/lists', [ListController::class, 'getUsersLists'])->name('user.lists');
        Route::get('/groups', [GroupController::class, 'index'])->name('user.groups');
        Route::get('/toggle-invites', [UserController::class, 'toggleGroupInvites'])->name('user.toggleInvites');
    });

    // Profile management
    Route::prefix('profile')->group(function () {
        Route::get('/', [UserController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [UserController::class, 'update'])->name('profile.update');
        Route::delete('/', [UserController::class, 'destroy'])->name('profile.destroy');
    });

    // Lists
    Route::prefix('lists')->group(function () {
        Route::get('/', [ListController::class, 'getLists'])->name('lists');
        Route::get('/create', [ListController::class, 'create'])->name('lists.create');
        Route::post('/store', [ListController::class, 'store'])->name('lists.store');
        Route::get('/{id}', [ListController::class, 'show'])->name('lists.show');
        Route::post('/{id}/items', [ListController::class, 'storeItem'])->name('lists.items.store');
        Route::post('/{id}/import', [ListController::class, 'import'])->name('lists.import');
        Route::get('/{id}/export', [ListController::class, 'export'])->name('lists.export');
        Route::get('/{id}/export-report', [ListController::class, 'export_report'])->name('lists.exportReport');
        Route::get('/{id}/divide', [ListController::class, 'divide'])->name('lists.divide');
        Route::post('/{id}/duplicate', [ListController::class, 'duplicate'])->name('lists.duplicate');
        Route::patch('/{id}/reminder', [ListController::class, 'updateReminder'])->name('lists.updateReminder');
        Route::post('/{list}/receipts', [ListController::class, 'uploadReceipt'])->name('lists.uploadReceipt');
    });

    Route::prefix('groups')->group(function () {
        Route::get('/', [GroupController::class, 'index'])->name('groups.index');
        Route::get('/create', [GroupController::class, 'create'])->name('groups.create');
        Route::post('/store', [GroupController::class, 'store'])->name('groups.store');
        Route::get('/{id}', [GroupController::class, 'show'])->name('groups.show');
        Route::get('/{id}/lists', [GroupController::class, 'getGroupShoppingLists']);
        Route::get('/{group}/add-members', [GroupController::class, 'addMembersForm'])->name('groups.addMembersForm');
        Route::post('/{group}/add-members', [GroupController::class, 'addMembers'])->name('groups.addMembers');
        Route::post('/{group}/leave', [GroupController::class, 'leave'])->name('groups.leave');
        Route::get('/{group}/detailed-show', [GroupController::class, 'detailedShow'])->name('groups.detailedShow');
        Route::post('/{groupId}/invite', [NotificationController::class, 'posljiPovabilo']);
    });

    Route::prefix('opozorila')->group(function () {
        Route::get('/', [NotificationController::class, 'prikaziOpozorila'])->name('opozorila.index');
        Route::post('/oznaci-prebrano', [NotificationController::class, 'oznaciPrebrano'])->name('opozorila.oznaciPrebrano');
        Route::get('/stevilo-neprebranih', [NotificationController::class, 'steviloNeprebranih'])->name('opozorila.steviloNeprebranih');
        Route::post('/sprejmi/{id}', [NotificationController::class, 'sprejmiPovabilo'])->name('opozorila.sprejmiPovabilo');
    });

    Route::get('/tag/{tag}', [TagController::class, 'index'])->name('tag.show');

    Route::patch('/items/{id}/mark-purchased', [ListController::class, 'markAsPurchased'])->name('items.markPurchased');

    // Tole je black box za mene lol kramar fix pls
    Route::middleware('auth')->get('/opozorila', [NotificationController::class, 'index'])->name('opozorila.index');
    Route::get('/opozorila', [NotificationController::class, 'prikaziOpozorila'])->name('opozorila.index');

    // Označevanje opozorila kot prebranega
    Route::middleware('auth')->post('/opozorila/oznaci-prebrano', [NotificationController::class, 'oznaciPrebrano'])->name('opozorila.oznaciPrebrano');
    Route::post('/opozorila/oznaci-prebrano', [NotificationController::class, 'oznaciPrebrano'])->name('opozorila.oznaciPrebrano');

    // Pridobitev števila neprebranih opozoril
    Route::middleware('auth')->get('/opozorila/stevilo-neprebranih', [NotificationController::class, 'steviloNeprebranih'])->name('opozorila.steviloNeprebranih');
    Route::get('/opozorila/stevilo-neprebranih', [NotificationController::class, 'steviloNeprebranih'])->name('opozorila.steviloNeprebranih');

    Route::post('/notification/{notificationId}/accept', [NotificationController::class, 'sprejmiPovabilo'])->middleware('auth');
    Route::post('/opozorila/sprejmi/{id}', [NotificationController::class, 'sprejmiPovabilo'])->name('opozorila.sprejmiPovabilo');

});

require __DIR__ . '/auth.php';
