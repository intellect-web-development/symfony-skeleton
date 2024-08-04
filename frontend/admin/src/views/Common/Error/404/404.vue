<template>
  <div class="container container-center">
    <div class="row">
      <div class="col">
        <div style="font-size: 30vh">404</div>

        <div class="text-h2" style="opacity:.4">
          {{ $t(tPath + 'label.main')}}
        </div>

        <router-link to="/">
          <Button :label="$t(tPath + 'button.go-home')" severity="secondary" />
        </router-link>
      </div>

    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: '404',
  data() {
    return {
      tPath: 'views.common.error.404.',
    }
  },
})
</script>


<style lang="scss" scoped>
@import "@/assets/smart-grid";

.row {
  @include row-flex();
  justify-content: center;

  .col {
    @include col();
    @include size(6);
    @include size-sm(10);
    @include size-xs(12);

    display: flex;
    flex-direction: column;
    align-items: center;
  }
}
</style>
