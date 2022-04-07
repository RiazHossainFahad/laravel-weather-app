<template>
  <p class="text-danger">{{ message ? message : "Invalid value" }}</p>
</template>

<script>
export default {
  props: ["message"],
};
</script>

<style>
</style>