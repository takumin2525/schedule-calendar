<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ログイン済みユーザーをグループ一覧へリダイレクト
Route::get('/dashboard', function () {
    return redirect()->route('groups.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Groups
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');

    // メンバー追加
    Route::post('/groups/{group}/members', [GroupController::class, 'addMember'])->name('groups.members.add');
    // メンバー削除
    Route::delete('/groups/{group}/members/{user}', [GroupController::class, 'removeMember'])->name('groups.members.remove');

    // Events
    Route::get('/groups/{group}/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/groups/{group}/events/date/{eventDate}', [EventController::class,'getByDate'])->name('events.byDate');
    Route::post('/groups/{group}/events', [EventController::class,'store'])->name('events.store');
    Route::put('/groups/{group}/events/{event}', [EventController::class,'update'])->name('events.update');
    Route::delete('/groups/{group}/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
});

require __DIR__ . '/auth.php';