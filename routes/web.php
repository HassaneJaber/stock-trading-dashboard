<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Services\StockService;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\WatchlistController;

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

Route::get('/stocks/{symbol}', function ($symbol, StockService $stockService) {
    return response()->json($stockService->getStockPrice($symbol));
});

Route::post('/buy-stock', [TransactionController::class, 'buyStock'])->middleware('auth');
Route::post('/sell-stock', [TransactionController::class, 'sellStock'])->middleware('auth');

Route::get('/portfolio', [PortfolioController::class, 'index'])->middleware('auth')->name('portfolio');

Route::middleware(['auth'])->group(function () {
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index'); // ✅ Fixed route name
    Route::post('/watchlist/add', [WatchlistController::class, 'addToWatchlist'])->name('watchlist.add');
    Route::delete('/watchlist/remove/{id}', [WatchlistController::class, 'removeFromWatchlist'])->name('watchlist.remove');
});

require __DIR__.'/auth.php';
