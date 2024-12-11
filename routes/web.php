<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\ProfileController;
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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('lists', [ProfileController::class, 'myLists'])->name('user.lists');

    Route::get('/lists/create', [ListController::class, 'create'])->name('lists.create');
    Route::post('/lists/store', [ListController::class, 'store'])->name('lists.store');

    Route::get('lists/{id}', [ListController::class, 'show'])->name('lists.show');
    Route::post('lists/{id}/items', [ListController::class, 'storeItem'])->name('lists.items.store');

    Route::get('/lists/{id}/export', [ListController::class, 'export'])->name('lists.export');
    Route::patch('/lists/{id}/reminder', [ListController::class, 'updateReminder'])->name('lists.updateReminder');

    Route::get('groups', [ProfileController::class, 'myGroups'])->name('user.groups');
    Route::get('groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('groups/store', [GroupController::class, 'store'])->name('groups.store');

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::middleware('auth')->get('/opozorila', [OpozoriloController::class, 'index'])->name('opozorila.index');

    // Označevanje opozorila kot prebranega
    Route::middleware('auth')->post('/opozorila/oznaci-prebrano', [OpozoriloController::class, 'oznaciPrebrano'])->name('opozorila.oznaciPrebrano');

    // Pridobitev števila neprebranih opozoril
    Route::middleware('auth')->get('/opozorila/stevilo-neprebranih', [OpozoriloController::class, 'steviloNeprebranih'])->name('opozorila.steviloNeprebranih');

    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

// Prikaz obrazca za dodajanje članov
    Route::get('/groups/{group}/add-members', [GroupController::class, 'addMembersForm'])->name('groups.addMembersForm');

// Shranjevanje izbranih članov v skupino
    Route::post('/groups/{group}/add-members', [GroupController::class, 'addMembers'])->name('groups.addMembers');

});

require __DIR__ . '/auth.php';
