import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: [
                'resources/js/app.js',
            ],
            // refresh: true
            refresh: [
                'resources/**',
                'resources/**/*.vue',
            ],
        }),
    ],
    resolve: {
        alias: [
            {
                find: /^~.+/,
                replacement: (val) => {
                    return val.replace(/^~/, "");
                },
            },
        ],
    },
    build: {
        commonjsOptions: {
            transformMixedEsModules: true,
        },
    }
});
