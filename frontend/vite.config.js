import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import tailwindcss from '@tailwindcss/vite'
import { fileURLToPath, URL } from 'node:url';

export default defineConfig({
    plugins: [
        vue(),
        tailwindcss(),
    ],
    css: {
        postcss: './postcss.config.js'
    },
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./src', import.meta.url)),
        },
    },
    server: {
        port: 5173,
        host: true,
        proxy: {
            '/api': {
                target: 'http://web',
                changeOrigin: true,
            },
        },
    },
});