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
                  <img src="/assets/images/logo.png"/>
                </Link>
              </div>
            </div>

            <div class="max-w-sm mx-auto min-w-[40%] px-4 py-8">
              <h1 class="text-3xl text-slate-800 font-bold mb-6">Welcome back! ✨</h1>
              <!-- Form -->
              <form @submit.prevent="login">
                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium mb-1" for="email">Email Address</label>
                    <input v-model="form.email" :class="{'border-rose-300': form.errors.email}" id="email" class="form-input w-full" type="email"/>
                    <div v-if="form.errors.email" class="text-rose-500 text-sm" v-text="form.errors.email"></div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-1" for="password">Password</label>
                    <input v-model="form.password" :class="{'border-rose-300': form.errors.password}" id="password" class="form-input w-full" type="password" autoComplete="on"/>
                    <div v-if="form.errors.password" class="text-rose-500 text-sm">Password is required</div>
                  </div>
                </div>
                <div class="flex items-center justify-between mt-6">
                  <div class="mr-1">
                    <Link class="text-sm underline hover:no-underline" href="/reset-password">Forgot Password?</Link>
                  </div>
                  <button class="btn bg-indigo-500 hover:bg-indigo-600 text-white ml-3" type="submit">Login</button>
                </div>
              </form>
              <!-- Footer -->
              <div class="pt-5 mt-6 border-t border-slate-200">
                <div class="text-sm inline-flex">
                  Don’t you have an account?
                  <Link href="/signup" class="ml-1" >
                    <div class="font-medium text-indigo-500 hover:text-indigo-600">
                      Signup
                    </div>
                  </Link>
                </div>
                <!-- Warning -->
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
import {Link, useForm} from '@inertiajs/vue3'
import GuestLayout from "../../Partials/GuestLayout.vue";

let props = defineProps({
  formData: Object,
  errors: Object
})
defineOptions({layout: GuestLayout})
const form = useForm(props.formData);

function login(){
  form.post('/login', {
    onSuccess: () => {
      form.reset();
    }
  });
}
</script>