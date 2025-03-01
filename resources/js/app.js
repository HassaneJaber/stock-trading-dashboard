import './bootstrap';

import Alpine from 'alpinejs';
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY, 
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

window.Echo.channel("stocks")
    .listen(".stock.price.updated", (data) => {
        console.log("Stock Price Updated: ", data);

        let stockElement = document.querySelector(`#stock-${data.stockSymbol}`);
        
        if (stockElement) {
            // ✅ Update stock price
            stockElement.innerText = `$${Number(data.newPrice).toFixed(6)}`;

            // ✅ Flash effect to highlight update
            stockElement.style.transition = "background 0.5s ease-in-out";
            stockElement.style.backgroundColor = "#f0f8ff"; // Light blue highlight
            setTimeout(() => stockElement.style.backgroundColor = "", 1000);
        }
    });

window.Alpine = Alpine;
Alpine.start();
