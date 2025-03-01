@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Stock Watchlist</h2>

    <!-- Form to Add Stocks to Watchlist -->
    <form action="{{ route('watchlist.add') }}" method="POST" class="mb-4">
        @csrf
        <div class="flex items-center">
            <input type="text" name="stock_symbol" placeholder="Enter Stock Symbol (e.g. AAPL)" required class="border p-2 mr-2 w-64">
            <input type="number" name="target_price" placeholder="Target Price (Optional)" step="0.000001" class="border p-2 mr-2 w-40">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2">Add to Watchlist</button>
        </div>
    </form>

    <!-- Watchlist Table -->
    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200 text-center">
                <th class="px-6 py-3 border">Stock</th>
                <th class="px-6 py-3 border">Current Price</th>
                <th class="px-6 py-3 border">Target Price</th>
                <th class="px-6 py-3 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($watchlist as $stock)
                <tr class="border text-center">
                    <td class="px-6 py-3 border font-semibold">{{ $stock->stock_symbol }}</td>

                    <!-- Updated Current Price Logic -->
                    <td class="px-6 py-3 border">
                        @if(is_numeric($stock->current_price))
                            ${{ number_format($stock->current_price, 6) }}
                        @else
                            <span class="text-red-500">API Error</span>
                        @endif
                    </td>

                    <!-- Target Price Highlighting -->
                    <td class="px-6 py-3 border font-bold 
                        @if($stock->target_price && is_numeric($stock->current_price))
                            @if($stock->current_price >= $stock->target_price) 
                                text-green-600
                            @else 
                                text-red-600
                            @endif
                        @endif">
                        {{ $stock->target_price ? '$' . number_format($stock->target_price, 6) : 'N/A' }}
                    </td>

                    <!-- Remove Stock from Watchlist -->
                    <td class="px-6 py-3 border">
                        <form action="{{ route('watchlist.remove', $stock->id) }}" method="POST" onsubmit="return confirm('Remove from watchlist?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-4 py-2">Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Auto Refresh every 60 seconds -->
<script>
    setInterval(() => {
        location.reload();
    }, 60000); // Refresh page every 60 seconds
</script>

@endsection
