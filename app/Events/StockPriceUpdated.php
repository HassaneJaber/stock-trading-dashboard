<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class StockPriceUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $stockSymbol;
    public $newPrice;

    public function __construct($stockSymbol, $newPrice)
    {
        $this->stockSymbol = $stockSymbol;
        $this->newPrice = $newPrice;
    }

    public function broadcastOn()
    {
        return new Channel('stocks');
    }

    public function broadcastAs()
    {
        return 'stock.price.updated';
    }
}
