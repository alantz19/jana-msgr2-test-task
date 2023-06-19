import {defineStore} from "pinia";
import {ref} from "vue";
export const useAppStore =
    defineStore('app', () => {
    const sidebarOpen = ref(false);
    const navigation = ref([]); //done in Sidebar.vue

    return {sidebarOpen, navigation}
});