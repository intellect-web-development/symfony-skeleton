<template>
  <div class="container container-center">
    <div class="row">
      <Card class="card">
        <template #title>
          <p class="card__title">{{ $t(tPath + 'title') }}</p>
        </template>
        <template #content>
          <p class="card__example">
            <span class="card__example-demo0">*</span>
            <span class="card__example-demo1">*</span>
            <span class="card__example-demo2">*</span>
          </p>
          <p class="card__content">
            {{ $t(tPath + 'content') }}
          </p>
          <p class="card__example">
            <span class="card__example-demo0">*</span>
            <span class="card__example-demo1">*</span>
            <span class="card__example-demo2">*</span>
          </p>
        </template>
      </Card>
    </div>
  </div>
</template>

<script lang="ts">
import {useHead} from "unhead";
import {projectName} from "@/router/routerDictionary";

export default {
  created() {
    useHead({
      title: this.$t(this.tPath + 'meta.title') + ' | ' + projectName,
    })
  },
  data() {
    return {
      tPath: 'pages.protected.welcome.',
    }
  }
}
</script>

<style lang="scss" scoped>
@import "@/assets/smart-grid";

.row {
  @include row-flex();
  @include lg(justify-content, center);

  .card {
    @include col();
    @include size(12);

    &__title {
      color: var(--green-700);
      text-align: center;
    }

    &__content {
      color: var(--blue-600);
      text-align: center;
    }

    &__example {
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      color: var(--green-700);

      &-demo1 {
        color: var(--red-700);
      }
      &-demo2 {
        color: var(--blue-700);
      }
    }
  }
}
</style>
