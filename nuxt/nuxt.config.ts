import {defineNuxtConfig} from "nuxt/config";

// https://v3.nuxtjs.org/api/configuration/nuxt.config
export default defineNuxtConfig({
    modules: ["@nuxtjs/tailwindcss", "@nuxt/image", '@pinia/nuxt', 'nuxt-headlessui'],
    runtimeConfig: {
        public: {
            backendUrl: "http://localhost",
            frontendUrl: "http://localhost:3000",
        },
    },
    imports: {
        dirs: ["./utils"],
    },
    devtools: {
        enabled: true
    }
});
