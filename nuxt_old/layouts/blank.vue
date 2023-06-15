<script lang="ts" setup>
useHead({
  title: 'Login',
  meta: [
    {
      hid: 'description',
      name: 'description',
      content: 'Login page'
    }
  ]
});
</script>

<template>
  <template>
    <div id="app-layout">
      <main>
        <slot/>
      </main>
    </div>
  </template>
</template>

<style scoped>

</style>