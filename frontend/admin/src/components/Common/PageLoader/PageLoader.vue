<template>
  <div style="position: relative; width: 100%; height:100%">
    <div class="info">
      <Card style="opacity: 85%; background: #18181b70; min-width: 15rem; text-align: center;">
        <template #content>
          <div style="text-align: center">
            <i class="pi pi-spin pi-spinner" style="font-size: 4rem"></i>
          </div>
        </template>
        <template #footer>
          {{ labelText }}
        </template>
      </Card>
    </div>
    <Skeleton width="100%" height="100%"></Skeleton>
  </div>
</template>

<script lang="ts">
export default {
  props: {
    label: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      tPath: 'components.common.page_loader.',
    }
  },
  computed: {
    labelText() {
      if (null !== this.label) {
        return this.$t(this.label);
      }

      return this.$t(this.tPath + 'loading');
    }
  }
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
