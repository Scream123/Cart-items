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
        host: 'www.phoenix-industry.local',
        port: 5173,
        hmr: {
            host: 'www.phoenix-industry.local',
            protocol: 'http',
            port: 5173,
        },
    },
});
