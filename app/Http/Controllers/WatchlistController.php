<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Watchlist;
use Illuminate\Support\Facades\Auth;
use App\Services\StockService;
use App\Events\StockPriceUpdated;

class WatchlistController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index()
    {
        $userId = Auth::id();
        $watchlist = Watchlist::where('user_id', $userId)->get();

        foreach ($watchlist as $stock) {
            $currentPriceData = $this->stockService->getStockPrice($stock->stock_symbol);

            // Ensure we handle errors properly
            if (isset($currentPriceData['price']) && is_numeric($currentPriceData['price'])) {
                $stock->current_price = round($currentPriceData['price'], 6);
            } else {
                $stock->current_price = 'API Error'; // Show error in UI
            }
        }

        return view('watchlist', compact('watchlist'));
    }

    public function addToWatchlist(Request $request)
    {
        $request->validate([
            'stock_symbol' => 'required|string|regex:/^[A-Za-z]+$/|max:10',
            'target_price' => 'nullable|numeric'
        ], [
            'stock_symbol.regex' => 'Stock symbol can only contain letters (A-Z).'
        ]);

        $userId = Auth::id();
        $symbol = strtoupper($request->stock_symbol);

        // Check if stock is already in watchlist
        if (Watchlist::where('user_id', $userId)->where('stock_symbol', $symbol)->exists()) {
            return redirect()->back()->with('error', 'Stock is already in your watchlist.');
        }

        Watchlist::create([
            'user_id' => $userId,
            'stock_symbol' => $symbol,
            'target_price' => $request->target_price
        ]);

        return redirect()->back()->with('success', 'Stock added to watchlist!');
    }

    public function updateStockPrices()
{
    $watchlist = Watchlist::all();

    foreach ($watchlist as $stock) {
        $newPrice = app(StockService::class)->getStockPrice($stock->stock_symbol)['price'] ?? null;

        if ($newPrice) {
            broadcast(new StockPriceUpdated($stock->stock_symbol, $newPrice));
        }
    }

    return response()->json(['message' => 'Stock prices updated and broadcasted']);
}

    public function removeFromWatchlist($id)
    {
        $watchlistItem = Watchlist::findOrFail($id);
        if ($watchlistItem->user_id == Auth::id()) {
            $watchlistItem->delete();
        }
        return redirect()->back()->with('success', 'Stock removed from watchlist.');
    }
}
