<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'stock_symbol', 'target_price'];
    protected $table = 'watchlist'; // ✅ Fix the table name
}
