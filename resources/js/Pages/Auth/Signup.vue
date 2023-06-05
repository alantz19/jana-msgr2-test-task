<template>
  <main class="bg-white">

    <div class="relative flex">

      <!-- Content -->
      <div class="w-full md:w-1/2">
        <div class="min-h-screen h-full flex flex-col after:flex-1">

          <div class="flex-1">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
              <!-- Logo -->
              <Link class="block" href="/">
                <img src="../../images/comm/logo.png"/>
              </Link>
            </div>
          </div>          

          <div class="max-w-sm mx-auto px-4 py-8">
            <h1 class="text-3xl text-slate-800 font-bold mb-6">Create your Account ✨</h1>
            <!-- Form -->
            <form @submit.prevent="signup">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium mb-1" for="email">Email Address <span class="text-rose-500">*</span></label>
                  <input v-model="form.email" id="email" class="form-input w-full" type="email" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1" for="name">Full Name <span class="text-rose-500">*</span></label>
                  <input id="name" class="form-input w-full" type="text" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1" for="role">Company Website <span class="text-rose-500">*</span></label>
                  <input id="name" class="form-input w-full" type="text" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1" for="password">Password</label>
                  <input id="password" class="form-input w-full" type="password" autoComplete="on" />
                </div>
              </div>
              <div class="flex items-center justify-between mt-6">
                <div class="mr-1">
                  <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox" />
                    <span class="text-sm ml-2">I confirm the <a href="https://comm.com/terms" target="_blank" class="font-medium text-indigo-500 hover:text-indigo-600">terms and conditions</a></span>
                  </label>
                </div>
                <Link class="btn bg-indigo-500 hover:bg-indigo-600 text-white ml-3 whitespace-nowrap" href="#">Sign Up</Link>
              </div>
            </form>
            <!-- Footer -->
            <div class="pt-5 mt-6 border-t border-slate-200">
              <div class="text-sm">
                Have an account? <Link class="font-medium text-indigo-500 hover:text-indigo-600" href="/login">Login</Link>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Image -->
      <div class="hidden md:block absolute top-0 bottom-0 right-0 md:w-1/2" aria-hidden="true">
        <img class="object-cover object-center w-full h-full" src="../../images/auth-image.jpg" width="760" height="1024" alt="Authentication" />
        <img class="absolute top-1/4 left-0 -translate-x-1/2 ml-8 hidden lg:block" src="../../images/auth-decoration.png" width="218" height="224" alt="Authentication decoration" />
      </div>

    </div>

  </main>
</template>

<script>
import { Link } from '@inertiajs/vue3'

export default {
  name: 'Signup',
  components: {
    Link
  },
  methods: {
    signup() {
      this.form.post('/signup', {
        onSuccess: () => {
          this.$inertia.visit('/')
        }
      })
    },
  }
}
</script>