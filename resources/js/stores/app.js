import {defineStore} from "pinia";
import {ref} from "vue";
export const useAppStore =
    defineStore('app', () => {
    const sidebarOpen = ref(false);

    return {sidebarOpen}
});