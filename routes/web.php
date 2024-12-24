<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpozoriloController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [UserController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/user/lists', [ListController::class, 'getUsersLists'])->name('user.lists');
    Route::get('/user/groups', [GroupController::class, 'index'])->name('user.groups');

    Route::get('/lists', [ListController::class, 'getLists'])->name('lists');
    Route::get('/lists/create', [ListController::class, 'create'])->name('lists.create');
    Route::post('/lists/store', [ListController::class, 'store'])->name('lists.store');
    Route::post('/lists/{list}/receipts', [ListController::class, 'uploadReceipt'])->name('lists.uploadReceipt');

    Route::get('/lists/{id}', [ListController::class, 'show'])->name('lists.show');
    Route::post('/lists/{id}/items', [ListController::class, 'storeItem'])->name('lists.items.store');

    Route::post('/lists/{id}/import', [ListController::class, 'import'])->name('lists.import');
    Route::get('/lists/{id}/export', [ListController::class, 'export'])->name('lists.export');
    Route::get('/lists/{id}/export-report', [ListController::class, 'export_report'])->name('lists.exportReport');


    Route::patch('/lists/{id}/reminder', [ListController::class, 'updateReminder'])->name('lists.updateReminder');
    Route::patch('/items/{id}/mark-purchased', [ListController::class, 'markAsPurchased'])->name('items.markPurchased');


    Route::get('groups', [UserController::class, 'myGroups'])->name('user.groups');
    Route::get('groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('groups/store', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{id}/lists', [GroupController::class, 'getGroupShoppingLists']);

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::middleware('auth')->get('/opozorila', [OpozoriloController::class, 'index'])->name('opozorila.index');

    // Označevanje opozorila kot prebranega
    Route::middleware('auth')->post('/opozorila/oznaci-prebrano', [OpozoriloController::class, 'oznaciPrebrano'])->name('opozorila.oznaciPrebrano');
    Route::post('/opozorila/oznaci-prebrano', [OpozoriloController::class, 'oznaciPrebrano'])->name('opozorila.oznaciPrebrano');


    // Pridobitev števila neprebranih opozoril
    Route::middleware('auth')->get('/opozorila/stevilo-neprebranih', [OpozoriloController::class, 'steviloNeprebranih'])->name('opozorila.steviloNeprebranih');
    Route::get('/opozorila/stevilo-neprebranih', [OpozoriloController::class, 'steviloNeprebranih'])->name('opozorila.steviloNeprebranih');

    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Prikaz obrazca za dodajanje članov
    Route::get('/groups/{group}/add-members', [GroupController::class, 'addMembersForm'])->name('groups.addMembersForm');

    // Shranjevanje izbranih članov v skupino
    Route::post('/groups/{group}/add-members', [GroupController::class, 'addMembers'])->name('groups.addMembers');




});

require __DIR__ . '/auth.php';
