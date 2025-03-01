@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold">Stock Trading Dashboard</h2>

    <div class="mb-4">
        <input type="text" id="stockSymbol" class="border p-2" placeholder="Enter Stock Symbol (e.g. AAPL)">
        <button id="getPriceBtn" onclick="fetchStock()" class="bg-blue-500 text-white p-2">Get Price</button>
    </div>

    <div id="stockData" class="p-4 border">
        <p>Enter a stock symbol to fetch the latest price.</p>
    </div>

    <div class="mt-4">
        <input type="number" id="quantity" class="border p-2" placeholder="Quantity">
        <button id="buyStockBtn" onclick="buyStock()" class="bg-green-500 text-white p-2">Buy</button>
        <button id="sellStockBtn" onclick="sellStock()" class="bg-red-500 text-white p-2">Sell</button>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("getPriceBtn").disabled = false;
    document.getElementById("buyStockBtn").disabled = false;
    document.getElementById("sellStockBtn").disabled = false;
});

async function fetchStock() {
    let symbol = document.getElementById("stockSymbol").value;
    let response = await fetch(`/stocks/${symbol}`);
    let data = await response.json();

    if (data.error) {
        document.getElementById("stockData").innerHTML = `<p class="text-red-500">${data.error}</p>`;
    } else {
        document.getElementById("stockData").innerHTML = `<p><strong>${data.symbol}</strong>: $${data.price}</p>`;
    }
}

async function buyStock() {
    let symbol = document.getElementById("stockSymbol").value;
    let quantity = document.getElementById("quantity").value;
    let price = document.getElementById("stockData").innerText.split("$")[1];

    let response = await fetch('/buy-stock', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ stock_symbol: symbol, quantity: quantity, price: price })
    });

    let result = await response.json();
    alert(result.message);
}

async function sellStock() {
    let symbol = document.getElementById("stockSymbol").value;
    let quantity = document.getElementById("quantity").value;
    let price = document.getElementById("stockData").innerText.split("$")[1];

    let response = await fetch('/sell-stock', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ stock_symbol: symbol, quantity: quantity, price: price })
    });

    let result = await response.json();
    alert(result.message);
}
</script>
@endsection
