<template>
  <div style="position: relative; width: 100%; height:100%">
    <div class="info">
      <Card style="opacity: 85%; background: #18181b70">
        <template #content>
          <div style="text-align: center">
            <i class="pi pi-spin pi-sync" style="font-size: 4rem"></i>
          </div>
        </template>
        <template #footer>
          {{ $t(tPath + 'check') }}
        </template>
      </Card>
    </div>
    <Skeleton width="100%" height="100%"></Skeleton>
  </div>
</template>

<script lang="ts">
export default {
  data() {
    return {
      tPath: 'components.security.security_loader.',
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
