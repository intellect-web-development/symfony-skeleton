<template>
  <div style="position: relative; width: 100%; height:100%">
    <div class="info">
      <Card style="opacity: 85%; background: rgba(24,24,27,0)">
        <template #title>
          {{ $t('components.security.no-access') }}
        </template>
        <template #content>
          <div style="text-align: center">
            <mdicon style="color: var(--red-500)" name="shield-lock" width="64" height="64" />
          </div>
        </template>
      </Card>
    </div>
  </div>
</template>

<script lang="ts">
export default {
  data() {
    return {
      tPath: 'components.security.security_guard.',
    }
  },
}
</script>

<style lang="scss" scoped>
.info {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 999;
}
</style>
