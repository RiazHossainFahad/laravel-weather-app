<template>
  <p class="text-danger">
    {{ message ? message : "Min " + min_length + " characters required!" }}
  </p>
</template>

<script>
export default {
  props: ["message", "min_length"],
};
</script>

<style>
</style>