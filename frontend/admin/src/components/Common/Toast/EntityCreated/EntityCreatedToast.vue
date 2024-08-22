<template>
  <Toast position="bottom-right" group="entity_created">
    <template #message="slotProps">
      <router-link v-if="slotProps.message.to" :to="slotProps.message.to">
        <Button severity="success" icon="pi pi-external-link" v-tooltip.left="$t(tPath + 'go')" />
      </router-link>
      <div class="p-toast-message-text">
        <span class="p-toast-summary">
          {{ slotProps.message.summary }}
        </span>
        <div class="p-toast-detail">
          <span>{{ slotProps.message.detail }}</span>
        </div>
      </div>
    </template>
  </Toast>
</template>

<script lang="ts">
export default {
  data() {
    return {
      tPath: 'components.common.toast.entity_created.',
    }
  },
}
</script>

<style lang="scss" scoped>
</style>
