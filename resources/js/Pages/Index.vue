<template>
  hello {{ name }}
</template>
<script>
  export default {
    mounted() {
      console.log('Component mounted.')
    },
    props: {
      name: {
        type: String,
        required: true
      }
    }
  }
</script>