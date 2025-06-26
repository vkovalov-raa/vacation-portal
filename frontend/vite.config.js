import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import { fileURLToPath, URL } from 'node:url';

export default defineConfig({
    plugins: [vue()],
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
                target: process.env.VITE_BACKEND_URL,
                changeOrigin: true,
            },
        },
    },
});