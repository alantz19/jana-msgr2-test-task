<script lang="ts" setup>

import FormInput from "~/components/FormInput.vue";
</script>
<template>
  <client-only>
    <div class="flex flex-1 flex-col justify-center px-6 py-12 lg:px-8 min-h-screen">
      <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <nuxt-img class="mx-auto h-10 w-auto" src="/logo.png"/>
        <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Create an account</h2>
      </div>

      <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="space-y-6" @submit.prevent="submitRegister">
          <div>
            <FormInput error="" label="Email address"/>
          </div>

          <div>
            <FormInput error="" label="Password" type="password"></FormInput>
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input id="remember-me" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                     name="remember-me"
                     type="checkbox"/>
              <label class="ml-3 block text-sm leading-6 text-gray-900" for="remember-me">I agree to the <a
                  class="text-indigo-600" href="https://comm.com/legal">terms and conditions</a></label>
            </div>
          </div>

          <div>
            <button
                class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                type="submit">
              Register
            </button>
          </div>
        </form>

        <p class="mt-10 text-center text-sm text-gray-500">
          Already have an account?
          {{ ' ' }}
          <NuxtLink to="/login">Login</NuxtLink>
        </p>
      </div>
    </div>
  </client-only>
</template>
