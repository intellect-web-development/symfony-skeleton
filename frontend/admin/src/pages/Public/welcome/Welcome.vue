<template>
  <div class="container container-center">
    <div class="row">
      <Card class="card">
        <template #title>
          <p class="card__title">
            {{ $t(tPath + 'title') }}
          </p>
        </template>
        <template #content>
          <p class="card__content">
            {{ $t(tPath + 'content') }}
          </p>
          <div class="card__authLink">
            <router-link :to="{name: 'Login'}">
              <Button :label="$t(tPath + 'link_button')" severity="secondary" />
            </router-link>
          </div>
        </template>
      </Card>
    </div>
  </div>
</template>
<script lang="ts">

import {projectName} from "@/router/routerDictionary";
import {useHead} from "unhead";

export default {
  created() {
    useHead({
      title: this.$t(this.tPath + 'title') + ' | ' + projectName,
    })
  },
  data() {
    return {
      tPath: 'pages.public.welcome.',
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

    &__authLink {
      text-align: center;

      button {
        margin-top: 1rem;
      }
    }
  }
}
</style>
