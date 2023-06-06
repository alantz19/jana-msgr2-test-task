<template>
  <GuestLayout>
    <main class="bg-white">

      <div class="relative flex">

        <!-- Content -->
        <div class="w-full md:w-1/2">
          <div class="min-h-screen h-full flex flex-col after:flex-1">

            <div class="flex-1">
              <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <!-- Logo -->
                <Link href="/" class="block">
                  <img src="../../images/comm/logo.png"/>
                </Link>
              </div>
            </div>

            <div class="max-w-sm mx-auto px-4 py-8">
              <h1 class="text-3xl text-slate-800 font-bold mb-6">Welcome back! ✨</h1>
              <!-- Form -->
              <form>
                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium mb-1" for="email">Email Address</label>
                    <input id="email" class="form-input w-full" type="email"/>
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-1" for="password">Password</label>
                    <input id="password" class="form-input w-full" type="password" autoComplete="on"/>
                  </div>
                </div>
                <div class="flex items-center justify-between mt-6">
                  <div class="mr-1">
                    <Link class="text-sm underline hover:no-underline" to="/reset-password">Forgot Password?</Link>
                  </div>
                  <Link class="btn bg-indigo-500 hover:bg-indigo-600 text-white ml-3" to="/">Sign In</Link>
                </div>
              </form>
              <!-- Footer -->
              <div class="pt-5 mt-6 border-t border-slate-200">
                <div class="text-sm">
                  Don’t you have an account?
                  <Link href="/signup">
                    <div class="font-medium text-indigo-500 hover:text-indigo-600">
                      Signup
                    </div>
                  </Link>
                </div>
                <!-- Warning -->
                <div class="mt-5">
                  <div class="bg-amber-100 text-amber-600 px-3 py-2 rounded">
                    <svg class="inline w-3 h-3 shrink-0 fill-current mr-2" viewBox="0 0 12 12">
                      <path
                          d="M10.28 1.28L3.989 7.575 1.695 5.28A1 1 0 00.28 6.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 1.28z"/>
                    </svg>
                    <span class="text-sm">
                    To support you during the pandemic super pro features are free until March 31st.
                  </span>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Image -->
        <div class="hidden md:block absolute top-0 bottom-0 right-0 md:w-1/2" aria-hidden="true">
          <img class="object-cover object-center w-full h-full" src="../../images/auth-image.jpg" width="760"
               height="1024"
               alt="Authentication"/>
          <img class="absolute top-1/4 left-0 -translate-x-1/2 ml-8 hidden lg:block"
               src="../../images/auth-decoration.png"
               width="218" height="224" alt="Authentication decoration"/>
        </div>

      </div>

    </main>
  </GuestLayout>
</template>

<script setup>
import {Link} from '@inertiajs/vue3'
import GuestLayout from "../../Partials/GuestLayout.vue";

defineOptions({layout: GuestLayout})
</script>