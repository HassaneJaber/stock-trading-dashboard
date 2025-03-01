@extends('layouts.app')

@section('content')

<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Your Stock Portfolio</h2>

    <h3 class="text-xl font-semibold mt-4">Owned Stocks</h3>
    <div class="overflow-x-auto">
        <table class="table-auto w-full border-collapse border border-gray-300 mt-2">
            <thead>
                <tr class="bg-gray-200 text-center">
                    <th class="px-6 py-3 border">Stock</th>
                    <th class="px-6 py-3 border">Quantity</th>
                    <th class="px-6 py-3 border">Investment</th>
                    <th class="px-6 py-3 border">Current Value</th>
                    <th class="px-6 py-3 border">Profit/Loss</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($processedStocks as $stock)
                    <tr class="border text-center">
                        <td class="px-6 py-3 border font-semibold">{{ $stock['stock_symbol'] }}</td>

                        <!-- Display "Sold Out" for fully sold stocks -->
                        <td class="px-6 py-3 border">
                            @if($stock['total_quantity'] == 0)
                                <span class="text-red-500">Sold Out</span>
                            @else
                                {{ $stock['total_quantity'] }}
                            @endif
                        </td>

                        <td class="px-6 py-3 border">${{ number_format($stock['total_investment'], 6) }}</td>

                        <!-- Real-time Update for Current Value -->
                        <td class="px-6 py-3 border" id="stock-{{ $stock['stock_symbol'] }}">
                            @if($stock['total_quantity'] == 0)
                                <span class="text-gray-500">$0.00 (Sold Out)</span>
                            @else
                                ${{ number_format($stock['current_value'], 6) }}
                            @endif
                        </td>

                        <!-- Display N/A for profit/loss if fully sold -->
                        <td class="px-6 py-3 border font-bold 
                            {{ $stock['profit_loss'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            @if($stock['total_quantity'] == 0)
                                <span class="text-gray-500">N/A (Sold Out)</span>
                            @else
                                {{ $stock['profit_loss'] > 0 ? '▲' : ($stock['profit_loss'] < 0 ? '▼' : '') }}
                                ${{ number_format($stock['profit_loss'], 6) }} 
                                ({{ number_format($stock['profit_loss_percentage'], 2) }}%)
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h3 class="text-xl font-semibold mt-6">Transaction History</h3>
    <div class="overflow-x-auto">
        <table class="table-auto w-full border-collapse border border-gray-300 mt-2">
            <thead>
                <tr class="bg-gray-200 text-center">
                    <th class="px-6 py-3 border">Stock</th>
                    <th class="px-6 py-3 border">Type</th>
                    <th class="px-6 py-3 border">Quantity</th>
                    <th class="px-6 py-3 border">Price</th>
                    <th class="px-6 py-3 border">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr class="border text-center">
                        <td class="px-6 py-3 border font-semibold">{{ $transaction->stock_symbol }}</td>
                        <td class="px-6 py-3 border">{{ ucfirst($transaction->type) }}</td>
                        <td class="px-6 py-3 border">{{ $transaction->quantity }}</td>
                        <td class="px-6 py-3 border">${{ number_format($transaction->price, 6) }}</td>
                        <td class="px-6 py-3 border">{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
