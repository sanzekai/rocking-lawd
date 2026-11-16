import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        // Mengizinkan domain ngrok mengakses server Vite lokal
        allowedHosts: ['barometer-statue-repeated.ngrok-free.dev'],
        cors: true
    }
});
//
