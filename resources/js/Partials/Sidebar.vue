<template>
  <div>
    <!-- Sidebar backdrop (mobile only) -->
    <div class="fixed inset-0 bg-slate-900 bg-opacity-30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
         :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'" aria-hidden="true"></div>

    <!-- Sidebar -->
    <div
        id="sidebar"
        ref="sidebar"
        class="flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-screen overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:!w-64 shrink-0 bg-slate-800 p-4 transition-all duration-200 ease-in-out"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'"
    >

      <!-- Sidebar header -->
      <div class="flex justify-between mb-10 pr-3 sm:px-2">
        <!-- Close button -->
        <button
            ref="trigger"
            class="lg:hidden text-slate-500 hover:text-slate-400"
            @click.stop="$emit('close-sidebar')"
            aria-controls="sidebar"
            :aria-expanded="sidebarOpen"
        >
          <span class="sr-only">Close sidebar</span>
          <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z"/>
          </svg>
        </button>
        <!-- Logo -->
        <Link href="/" class="w-full mx-auto">
          <img src="/assets/images/logo.png"/>
        </Link>
      </div>

      <!-- Links -->
      <div class="space-y-8">
        <ul class="mt-3">
          <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0" :class="{'bg-slate-900': $page.url === '/'}">
            <Link href="/" class="block text-slate-200 hover:text-white truncate transition duration-150"
                  :class="{'hover:text-slate-200': $page.url === '/'}">
              <div class="flex items-center">
                <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24">
                  <path class="fill-current"
                        :class="{'text-blue-400': $page.url === '/', 'text-slate-600': $page.url !== '/'}"
                        d="M1 3h22v20H1z"/>
                  <path class="fill-current"
                        :class="{'text-blue-300': $page.url === '/', 'text-slate-400': $page.url !== '/'}"
                        d="M21 3h2v4H1V3h2V1h4v2h10V1h4v2Z"/>
                </svg>
                <span
                    class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Dashboard</span>
              </div>
            </Link>
          </li>
          <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0" :class="{'bg-slate-900': $page.url === '/campaigns'}">
            <Link href="/campaigns" class="block text-slate-200 hover:text-white truncate transition duration-150"
                  :class="{'hover:text-slate-200': $page.url === '/campaigns'}">
              <div class="flex items-center">
                <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24">
                  <path class="fill-current text-slate-600" :class="{'text-indigo-500': $page.url === '/campaigns'}"
                        d="M1 3h22v20H1z"/>
                  <path class="fill-current text-slate-400" :class="{'text-indigo-300': $page.url === '/campaigns'}"
                        d="M21 3h2v4H1V3h2V1h4v2h10V1h4v2Z"/>
                </svg>
                <span
                    class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Campaigns</span>
              </div>
            </Link>
          </li>
        </ul>


        <!-- Pages group -->
        <div>
          <h3 class="text-xs uppercase text-slate-500 font-semibold pl-3">
            <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
                  aria-hidden="true">•••</span>
            <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Pages</span>
          </h3>
          <ul class="mt-3">
            <Link href="/inbox">
              <div>Inbox</div>
            </Link>
          </ul>
        </div>
      </div>

      <!-- Expand / collapse button -->
      <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto">
        <div class="px-3 py-2">
          <button @click.prevent="sidebarExpanded = !sidebarExpanded">
            <span class="sr-only">Expand / collapse sidebar</span>
            <svg class="w-6 h-6 fill-current sidebar-expanded:rotate-180" viewBox="0 0 24 24">
              <path class="text-slate-400" d="M19.586 11l-5-5L16 4.586 23.414 12 16 19.414 14.586 18l5-5H7v-2z"/>
              <path class="text-slate-600" d="M3 23H1V1h2z"/>
            </svg>
          </button>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import {ref, onMounted, onUnmounted, watch} from 'vue'
// import { useRouter } from 'vue-router'

import {Link} from '@inertiajs/vue3'
import SidebarLinkGroup from './SidebarLinkGroup.vue'

export default {
  name: 'Sidebar',
  props: ['sidebarOpen'],
  components: {
    SidebarLinkGroup,
    Link
  },
  setup(props, {emit}) {

    const trigger = ref(null)
    const sidebar = ref(null)

    const storedSidebarExpanded = localStorage.getItem('sidebar-expanded')
    const sidebarExpanded = ref(storedSidebarExpanded === null ? false : storedSidebarExpanded === 'true')

    // const currentRoute = useRouter().currentRoute.value
    const currentRoute = '/inbox'

    // close on click outside
    const clickHandler = ({target}) => {
      if (!sidebar.value || !trigger.value) return
      if (
          !props.sidebarOpen ||
          sidebar.value.contains(target) ||
          trigger.value.contains(target)
      ) return
      emit('close-sidebar')
    }

    // close if the esc key is pressed
    const keyHandler = ({keyCode}) => {
      if (!props.sidebarOpen || keyCode !== 27) return
      emit('close-sidebar')
    }

    onMounted(() => {
      document.addEventListener('click', clickHandler)
      document.addEventListener('keydown', keyHandler)
    })

    onUnmounted(() => {
      document.removeEventListener('click', clickHandler)
      document.removeEventListener('keydown', keyHandler)
    })

    watch(sidebarExpanded, () => {
      localStorage.setItem('sidebar-expanded', sidebarExpanded.value)
      if (sidebarExpanded.value) {
        document.querySelector('body').classList.add('sidebar-expanded')
      } else {
        document.querySelector('body').classList.remove('sidebar-expanded')
      }
    })

    return {
      trigger,
      sidebar,
      sidebarExpanded,
      currentRoute,
    }
  },
}
</script>