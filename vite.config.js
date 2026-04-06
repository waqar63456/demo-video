import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
    // build: {
    //     outDir: 'public/build',  // Ensures assets are built into the correct directory
    //     manifest: true,  // Generates a manifest file for Laravel to correctly reference assets
    //     rollupOptions: {
    //         output: {
    //             assetFileNames: 'assets/[name]-[hash][extname]', // Ensures hashed filenames
    //         },
    //     },
    // },
    // server: {
    //     host: '92.204.40.171', // Ensures Vite runs correctly on remote servers
    //     port: 5173,
    //     strictPort: true,
    // },
});





