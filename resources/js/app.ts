// import './bootstrap';
import './css/style.css'
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import Layout from "./Partials/Layout.vue";
import { createPinia } from 'pinia'

import Vue3EasyDataTable from 'vue3-easy-data-table';

const pinia = createPinia()

var app = createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./**/*.vue', { eager: true })
        let page =  pages[`./Pages/${name}.vue`]

        page.default.layout = page.default.layout || Layout
        return page;

    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .component('EasyDataTable', Vue3EasyDataTable)
            .mount(el)

    },
})