# 📈 Stock Trading Dashboard
A Laravel-powered stock portfolio and watchlist tracker with real-time price updates using Pusher.

🚀 Features
User Authentication – Secure login and registration system.
Stock Portfolio Management – Buy and sell stocks while tracking your investments.
Real-time Price Updates – Uses Pusher to dynamically update stock prices.
Watchlist – Add stocks to your watchlist and monitor price changes.
Transaction History – View a detailed log of your stock transactions.
Live Notifications – Get alerts when stock prices hit your target values.
🛠️ Tech Stack
Frontend: Tailwind CSS, Alpine.js
Backend: Laravel 12 (PHP 8.2)
Database: MySQL
Real-time Updates: Pusher & Laravel Echo
Version Control: Git & GitHub
📂 Installation & Setup
1️⃣ Clone the Repository
git clone https://github.com/HassaneJaber/stock-trading-dashboard.git
cd stock-trading-dashboard

2️⃣ Install Dependencies
composer install
npm install && npm run dev

3️⃣ Configure the Environment
Copy .env.example to .env:
cp .env.example .env

Generate the application key:
php artisan key:generate

Set up database credentials inside .env:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stock_trading_db
DB_USERNAME=root
DB_PASSWORD=

Add Pusher credentials inside .env:
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=1950436
PUSHER_APP_KEY=f9b607468c4b3e672c90
PUSHER_APP_SECRET=56f37202feaec1c5bdc5
PUSHER_APP_CLUSTER=mt1

4️⃣ Run Migrations
php artisan migrate --seed

5️⃣ Start the Server
php artisan serve

The application will be available at http://127.0.0.1:8000

📡 Running Real-Time Updates
Start Laravel Echo Server
npm run dev

Manually Simulate a Stock Price Update
Run the following command inside Tinker:
php artisan tinker

Then execute:
use App\Events\StockPriceUpdated;
event(new StockPriceUpdated('AAPL', 150.25));

✅ Stock price updates will reflect instantly in the UI.

📜 API Endpoints
GET /portfolio – View user's portfolio
POST /buy-stock – Buy a stock
POST /sell-stock – Sell a stock
GET /watchlist – View watchlist
POST /watchlist/add – Add stock to watchlist
DELETE /watchlist/remove/{id} – Remove from watchlist

📌 TODO / Future Enhancements
📊 Charts & Graphs to visualize stock performance.
📅 Stock Alerts when prices hit certain thresholds.
📉 Historical Price Data for better investment decisions.
📢 WebSocket Integration for even faster updates.
📄 License
This project is MIT licensed.

Let me know if you need any changes! 🚀
