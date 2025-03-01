# Stock Trading Dashboard 📈
A Laravel-powered stock trading dashboard that allows users to buy, sell, and track stocks in real-time. The system integrates Pusher for live stock price updates and provides a watchlist feature to track selected stocks.

🚀 Features
✅ User Authentication – Secure login & registration with Laravel Breeze.
✅ Real-time Stock Price Updates – Uses Pusher & Laravel Echo to display live stock prices.
✅ Portfolio Management – View your owned stocks, total investments, and profits/losses.
✅ Transaction History – Track all buy & sell transactions.
✅ Watchlist – Add/remove stocks to monitor price movements.
✅ Dynamic UI – Built with Tailwind CSS for a clean & responsive design.
✅ Data Persistence – Uses MySQL for storing transactions, stock prices, and watchlist data.

🛠️ Tech Stack
Laravel 12 – Backend framework
MySQL – Database
Pusher – Real-time stock updates
Tailwind CSS – Frontend styling
Live Stock API – Fetches stock prices dynamically
📂 Installation
1️⃣ Clone the Repository

git clone https://github.com/your-github-username/stock-trading-dashboard.git
cd stock-trading-dashboard
2️⃣ Install Dependencies

composer install
npm install
3️⃣ Set Up the Environment
Copy .env.example to .env


cp .env.example .env
Then update the following fields in the .env file with your own credentials:


DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

PUSHER_APP_ID=your_pusher_id
PUSHER_APP_KEY=your_pusher_key
PUSHER_APP_SECRET=your_pusher_secret
PUSHER_APP_CLUSTER=your_pusher_cluster
4️⃣ Set Up Database

php artisan migrate --seed
5️⃣ Start the Application

php artisan serve
Open http://127.0.0.1:8000 in your browser.

💡 How to Test Real-time Updates?
1️⃣ Open the Portfolio Page on two different browser windows.
2️⃣ Run Tinker in your terminal:


php artisan tinker
3️⃣ Trigger a Stock Price Update manually:


use App\Events\StockPriceUpdated;
event(new StockPriceUpdated('AAPL', 145.50));
4️⃣ Watch live updates appear instantly on the dashboard! 🎉

🛑 Important Security Notes
🚨 NEVER push the .env file to GitHub (it contains credentials).
🚨 .env is already ignored in .gitignore, so it won't be uploaded.
🚨 Pusher credentials are private – only share placeholders in the README.

📌 License
This project is open-source under the MIT License.

