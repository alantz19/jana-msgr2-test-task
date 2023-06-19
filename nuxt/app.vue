<script lang="ts" setup>
</script>

<template>
  <Head>
    <Link
        href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap"
        rel="stylesheet"
    />
  </Head>
  <Body class="antialiased">
  <NuxtLayout>
    <client-only>
      <NuxtPage/>
    </client-only>
  </NuxtLayout>
  </Body>
</template>
