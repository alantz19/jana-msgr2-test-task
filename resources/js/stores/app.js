import {defineStore} from "pinia";
import {ref} from "vue";
export const useAppStore =
    defineStore('app', () => {
    const sidebarOpen = ref(false);
    const navigation = ref([])

    return {sidebarOpen, navigation}
});