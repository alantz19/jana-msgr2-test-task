<template>
  <!-- Modal backdrop -->
  <transition
    enter-active-class="transition ease-out duration-200"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition ease-out duration-100"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div v-show="modalOpen" class="fixed inset-0 bg-slate-900 bg-opacity-30 z-50 transition-opacity" aria-hidden="true"></div>
  </transition>
  <!-- Modal dialog -->
  <transition
    enter-active-class="transition ease-in-out duration-200"
    enter-from-class="opacity-0 translate-y-4"
    enter-to-class="opacity-100 translate-y-0"
    leave-active-class="transition ease-in-out duration-200"
    leave-from-class="opacity-100 translate-y-0"
    leave-to-class="opacity-0 translate-y-4"
  >
    <div v-show="modalOpen" :id="id" class="fixed inset-0 z-50 overflow-hidden flex items-start top-20 mb-4 justify-center px-4 sm:px-6" role="dialog" aria-modal="true">
      <div ref="modalContent" class="bg-white overflow-auto max-w-2xl w-full max-h-full rounded shadow-lg">
        <!-- Search form -->
        <form class="border-b border-slate-200">
          <div class="relative">
            <label :for="searchId" class="sr-only">Search</label>
            <input :id="searchId" class="w-full border-0 focus:ring-transparent placeholder-slate-400 appearance-none py-3 pl-10 pr-4" type="search" placeholder="Search Anything…" ref="searchInput" />
            <button class="absolute inset-0 right-auto group" type="submit" aria-label="Search">
              <svg class="w-4 h-4 shrink-0 fill-current text-slate-400 group-hover:text-slate-500 ml-4 mr-2" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 14c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7zM7 2C4.243 2 2 4.243 2 7s2.243 5 5 5 5-2.243 5-5-2.243-5-5-5z" />
                <path d="M15.707 14.293L13.314 11.9a8.019 8.019 0 01-1.414 1.414l2.393 2.393a.997.997 0 001.414 0 .999.999 0 000-1.414z" />
              </svg>
            </button>
          </div>
        </form>
        <div class="py-4 px-2">
          <!-- Recent searches -->
          <div class="mb-3 last:mb-0">
            <div class="text-xs font-semibold text-slate-400 uppercase px-2 mb-2">Recent searches</div>
          </div>
          <!-- Recent pages -->
          <div class="mb-3 last:mb-0">
            <div class="text-xs font-semibold text-slate-400 uppercase px-2 mb-2">Recent pages</div>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import { ref, nextTick, onMounted, onUnmounted, watch } from 'vue'

export default {
  name: 'ModalSearch',
  props: ['id', 'searchId', 'modalOpen'],
  emits: ['open-modal', 'close-modal'],
  setup(props, { emit }) {

    const modalContent = ref(null)
    const searchInput = ref(null)
    
    // close on click outside
    const clickHandler = ({ target }) => {
      if (!props.modalOpen || modalContent.value.contains(target)) return
      emit('close-modal')
    }

    // close if the esc key is pressed
    const keyHandler = ({ keyCode }) => {
      if (!props.modalOpen || keyCode !== 27) return
      emit('close-modal')
    }

    onMounted(() => {
      document.addEventListener('click', clickHandler)
      document.addEventListener('keydown', keyHandler)
    })

    onUnmounted(() => {
      document.removeEventListener('click', clickHandler)
      document.removeEventListener('keydown', keyHandler)
    })

    watch(() => props.modalOpen, (open) => {
      open && nextTick(() => searchInput.value.focus())
    })    

    return {
      modalContent,
      searchInput,
    }
  }
}
</script>