<template>
  <p class="text-danger">{{ message ? message : "Mandatory field" }}</p>
</template>

<script>
export default {
  props: ["message"],
};
</script>

<style>
</style>