<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function buyStock(Request $request) {
        $request->validate([
            'stock_symbol' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
        ]);

        Transaction::create([
            'user_id' => Auth::id(),
            'stock_symbol' => $request->stock_symbol,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'type' => 'buy',
        ]);

        return response()->json(['message' => 'Stock purchased successfully!']);
    }

    public function sellStock(Request $request) {
        $request->validate([
            'stock_symbol' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
        ]);

        Transaction::create([
            'user_id' => Auth::id(),
            'stock_symbol' => $request->stock_symbol,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'type' => 'sell',
        ]);

        return response()->json(['message' => 'Stock sold successfully!']);
    }
}
