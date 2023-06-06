<script setup>

import Header from "./Header.vue";
import {ref} from "vue";
import Sidebar from "./Sidebar.vue";
const sidebarOpen = ref(false)
</script>

<template>
  <div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <Sidebar :sidebarOpen="sidebarOpen" @close-sidebar="sidebarOpen = false" />

    <!-- Content area -->
    <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">

      <!-- Site header -->
      <Header :sidebarOpen="sidebarOpen" @toggle-sidebar="sidebarOpen = !sidebarOpen" />

      <main>
        <slot />
      </main>
    </div>
  </div>
</template>