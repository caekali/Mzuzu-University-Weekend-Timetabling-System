import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // server: {
    //     host: '0.0.0.0', // allows Ngrok to access
    //     https: true,      // this is the key
    //     origin: 'https://0f5ea15d818e.ngrok-free.app', // your Ngrok HTTPS URL
    //     hmr: {
    //         protocol: 'wss',
    //         host: '0f5ea15d818e.ngrok-free.app',
    //     },
    // },

    // server: {
    //     host: '0.0.0.0',
    //     port: 5173,
    //     strictPort: true,
    //     hmr: {
    //         host: '192.168.43.33',
    //     },
    // },
});
