<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class StockService {
    public function getStockPrice($symbol) {
        $apiKey = env('STOCK_API_KEY');
        $cacheKey = "stock_price_{$symbol}";

        // ✅ Check cache first to reduce API calls
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $url = "https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&symbol={$symbol}&interval=5min&apikey={$apiKey}";

        $response = Http::get($url);
        $data = $response->json();

        if (isset($data["Time Series (5min)"])) {
            $latest = reset($data["Time Series (5min)"]);
            $price = $latest["1. open"];

            // ✅ Cache the stock price for 10 minutes to prevent excessive calls
            Cache::put($cacheKey, ["symbol" => $symbol, "price" => $price], now()->addMinutes(10));

            return ["symbol" => $symbol, "price" => $price];
        }

        return ["error" => "Stock not found or API limit exceeded"];
    }
}
