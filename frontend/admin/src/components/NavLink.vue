<template>
  <div>
    <Button :label="label" link :icon="icon" class="nav-link-button" />
  </div>
</template>

<script lang="ts">
export default {
  props: {
    label: {
      type: String,
      default: null,
    },
    icon: {
      type: String,
      default: null,
    },
  }
}
</script>

<style lang="scss" scoped>
.nav-link-button {
  width: 100%;
  text-align: left;
}
</style>
