<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConfirmationController;

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

Route::get('/', [ItemController::class, 'index'])->name('home');

// Route::middleware('auth')->post(
//     '/items/{item}/confirm',
//     [ConfirmationController::class, 'confirm']
// )->name('items.confirm');

// Route::post('/items/{item}/complete', [ItemController::class, 'complete'])
//     ->middleware('auth')
//     ->name('items.complete');

Route::post('/items/{item}/confirm-owner', [ItemController::class, 'confirmOwner'])
    ->middleware('auth')
    ->name('items.confirm.owner');

Route::post('/items/{item}/confirm-finder', [ItemController::class, 'confirmFinder'])
    ->middleware('auth')
    ->name('items.confirm.finder');

Route::middleware('auth')->group(function () {

    // edit laporan
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])
        ->name('items.edit');

    Route::put('/items/{item}', [ItemController::class, 'update'])
        ->name('items.update');

    // hapus laporan
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])
        ->name('items.destroy');

});


Route::middleware('auth')->group(function () {
    Route::get('/chats', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/start/{item}', [ChatController::class, 'start'])->name('chat.start');
    Route::get('/chat/{chat}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{chat}/send', [ChatController::class, 'send'])->name('chat.send');
});


Route::middleware('auth')->group(function () {
    Route::get('/items/found', [ItemController::class, 'foundList'])->name('items.found');
    Route::get('/items/lost', [ItemController::class, 'lostList'])->name('items.lost');

    Route::get('/items/create-found', [ItemController::class, 'createFound'])->name('items.createFound');
    Route::get('/items/create-lost', [ItemController::class, 'createLost'])->name('items.createLost');

    Route::post('/items/store-found', [ItemController::class, 'storeFound'])->name('items.storeFound');
    Route::post('/items/store-lost', [ItemController::class, 'storeLost'])->name('items.storeLost');
});

Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
