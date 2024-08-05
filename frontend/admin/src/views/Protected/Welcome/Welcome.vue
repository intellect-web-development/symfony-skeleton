<template>
  <div class="container container-center">
    <div class="row">
      <Card class="card">
        <template #title>
          <p class="card__title">{{ $t(tPath + 'title') }}</p>
        </template>
        <template #content>
          <span class="card__content">{{ $t(tPath + 'content') }}</span>
        </template>
      </Card>
    </div>
  </div>
</template>

<script lang="ts">
export default {
  data() {
    return {
      tPath: 'views.protected.welcome.',
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

    background: var(--app-black-25);
    p {
      text-align: center;
    }

    &__title {
      color: var(--green-700);
    }

    &__content {
      color: var(--green-700);
    }
  }
}
</style>
