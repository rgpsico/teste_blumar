import crypto from 'node:crypto';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';

// Vite's Vue plugin expects `crypto.hash` to exist. Node's Web Crypto
// implementation doesn't provide it, so we provide a minimal polyfill using
// `createHash` to keep builds working in environments without `crypto.hash`.
if (typeof globalThis.crypto?.hash !== 'function') {
    const hash = (algorithm, data, encoding) =>
        crypto.createHash(algorithm).update(data).digest(encoding);

    globalThis.crypto = Object.assign({}, crypto, globalThis.crypto, { hash });
}

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});
