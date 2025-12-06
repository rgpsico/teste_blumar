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

const cryptoDescriptor = Object.getOwnPropertyDescriptor(globalThis, 'crypto');
const existingCrypto = globalThis.crypto;

if (typeof existingCrypto === 'object' && existingCrypto !== null) {
    if (typeof existingCrypto.hash !== 'function') {
        try {
            existingCrypto.hash = hash;
        } catch {
            Object.defineProperty(existingCrypto, 'hash', { value: hash, configurable: true });
        }
    }
} else if (cryptoDescriptor?.writable || cryptoDescriptor?.set) {
    globalThis.crypto = Object.assign({}, cryptoWithHash, existingCrypto, { hash });
} else if (!cryptoDescriptor) {
    Object.defineProperty(globalThis, 'crypto', { value: cryptoWithHash, configurable: true, writable: true });
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
