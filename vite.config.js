import {defineConfig} from 'vite';
import laravel, {refreshPaths} from 'laravel-vite-plugin';
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
        alias: {
            '@': '/resources/js',
            '@resources': '/resources',
        },
    },
    server: {
        watch: {
            usePolling: true,
        }
    },
    build: {
        commonjsOptions: {
            transformMixedEsModules: true,
        },
    }
});
