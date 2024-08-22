<template>
  <div class="container container-center">
    <div class="row">
      <div class="col">
        <div class="code-error">
          {{ $t(tPath + 'label.code')}}
        </div>

        <div class="label">
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
import {useHead} from "unhead";
import {projectName} from "@/router/routerDictionary";

export default defineComponent({
  name: '404',

  created() {
    useHead({
      title: this.$t(this.tPath + 'title') + ' | ' + projectName,
    })
  },
  data() {
    return {
      tPath: 'pages.error.404.',
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

    .code-error {
      font-size: 30vh;
    }

    .label {
      opacity:.4;
      margin-bottom: 0.75rem;
    }
  }
}
</style>
