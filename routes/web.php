<?php

use App\Http\Controllers\ListController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('lists', [ProfileController::class, 'myLists'])->name('user.lists');

    Route::get('/lists', [ListController::class, 'getLists']);

    Route::get('lists/create', [ListController::class, 'create'])->name('lists.create');
    Route::post('lists/store', [ListController::class, 'store'])->name('lists.store');

    Route::get('lists/{id}', [ListController::class, 'show'])->name('lists.show');
    Route::post('lists/{id}/items', [ListController::class, 'storeItem'])->name('lists.items.store');

    Route::get('/lists/{id}/export', [ListController::class, 'export'])->name('lists.export');
    Route::patch('/lists/{id}/reminder', [ListController::class, 'updateReminder'])->name('lists.updateReminder');
});
Route::middleware('auth')->group(function () {
    Route::get('groups', [ProfileController::class, 'myGroups'])->name('user.groups');
    Route::get('groups/create', [ListController::class, 'create'])->name('groups.create');
    Route::post('groups/store', [ListController::class, 'store'])->name('groups.store');
    Route::get('/groups/{id}/lists', [GroupController::class, 'getGroupShoppingLists']);
});
require __DIR__.'/auth.php';
