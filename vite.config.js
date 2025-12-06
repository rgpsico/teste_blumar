import * as crypto from 'node:crypto';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';

// Vite's Vue plugin expects `crypto.hash` to exist, but older Node versions
// only expose `createHash`. We polyfill both the imported module and the global
// crypto object to keep builds working in those environments.
const hash = typeof crypto.hash === 'function'
    ? crypto.hash.bind(crypto)
    : (algorithm, data, encoding) =>
        crypto.createHash(algorithm).update(data).digest(encoding);

const cryptoWithHash = Object.assign({}, crypto, { hash });

if (typeof globalThis.crypto?.hash !== 'function') {
    const baseCrypto = typeof globalThis.crypto === 'object' ? globalThis.crypto : {};
    globalThis.crypto = Object.assign({}, cryptoWithHash, baseCrypto, { hash });
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
