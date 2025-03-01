<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Services\StockService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Events\StockPriceUpdated;

class PortfolioController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index()
    {
        $userId = Auth::id();

        // Fetch all transactions
        $transactions = Transaction::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        Log::info('User Transactions:', ['transactions' => $transactions->toArray()]);

        // Fetch owned stocks, including fully sold ones
        $ownedStocks = Transaction::selectRaw('stock_symbol, 
        GREATEST(SUM(CASE WHEN type="buy" THEN quantity ELSE -quantity END), 0) as total_quantity, 
        SUM(CASE WHEN type="buy" THEN quantity * price ELSE 0 END) as total_investment')
        ->where('user_id', $userId)
        ->groupBy('stock_symbol')
        ->get();

        Log::info('Fetched Owned Stocks:', ['ownedStocks' => $ownedStocks->toArray()]);

        $processedStocks = [];

        foreach ($ownedStocks as $stock) {
            $stock = (object) $stock;

            // Fetch current stock price from API
            $currentPriceData = $this->stockService->getStockPrice($stock->stock_symbol);
            Log::info("Stock: {$stock->stock_symbol}, API Response:", ['data' => $currentPriceData]);

            if (is_array($currentPriceData) && isset($currentPriceData['data']['price'])) {
                $currentPrice = (float) number_format((float) $currentPriceData['data']['price'], 6, '.', '');
            } elseif (is_numeric($currentPriceData)) {
                $currentPrice = (float) number_format((float) $currentPriceData, 6, '.', '');
            } else {
                // Fallback to average investment price
                $currentPrice = ($stock->total_quantity > 0) && ($stock->total_investment > 0)
    ? round($stock->total_investment / max($stock->total_quantity, 1), 6)
    : 0;
            }

            if (!is_numeric($currentPrice) || $currentPrice <= 0) {
                Log::warning("Stock: {$stock->stock_symbol} has an invalid price: $currentPrice");
                $currentPrice = 1;
            }

            if (isset($currentPriceData["error"])) {
                Log::warning("Using last known price for {$stock->stock_symbol}");
            
                $lastKnownPrice = Cache::get("stock_price_{$stock->stock_symbol}")["price"] ?? null;
            
                // Prevent division by zero
                if ($stock->total_quantity > 0) {
                    $currentPrice = $lastKnownPrice ?? ($stock->total_investment / $stock->total_quantity);
                } else {
                    $currentPrice = $lastKnownPrice ?? 0; // Set 0 if quantity is zero
                }
            }
            

            // Compute values with 6 decimal precision
            $stock->current_value = round($stock->total_quantity * $currentPrice, 6);
            $stock->profit_loss = round($stock->current_value - $stock->total_investment, 6);
            $stock->profit_loss_percentage = ($stock->total_investment > 0) 
                ? round(($stock->profit_loss / $stock->total_investment) * 100, 6) 
                : 0;

            $processedStocks[] = [
                'stock_symbol' => $stock->stock_symbol,
                'total_quantity' => (int) $stock->total_quantity,
                'total_investment' => round($stock->total_investment, 6),
                'current_value' => round($stock->current_value, 6),
                'profit_loss' => round($stock->profit_loss, 6),
                'profit_loss_percentage' => round($stock->profit_loss_percentage, 6)
            ];

            Log::info("Processed Data - Stock: {$stock->stock_symbol}, Quantity: {$stock->total_quantity}, Investment: {$stock->total_investment}, Current Value: {$stock->current_value}, Profit/Loss: {$stock->profit_loss}, Profit/Loss %: {$stock->profit_loss_percentage}%");

            // ✅ Broadcasting the stock price update
            broadcast(new StockPriceUpdated($stock->stock_symbol, $currentPrice));
            Log::info("Broadcasted Stock Price Update for {$stock->stock_symbol} with new price: {$currentPrice}");
        }

        Log::info('Final Processed Stocks:', ['stocks' => $processedStocks]);

        return view('portfolio', compact('transactions', 'processedStocks'));
    }
}
