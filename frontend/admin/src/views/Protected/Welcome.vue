<template>
  <div class="container container-center">
    <div class="info">
      <Card class="card">
        <template #title>
          {{ $t('views.panel.welcome.title') }}
        </template>
        <template #content>
          <p>{{ $t('views.panel.welcome.content') }}</p>
        </template>
      </Card>
    </div>
  </div>
</template>

<script lang="ts">
export default {
}
</script>

<style lang="scss" scoped>
@import "smart-grid/smart-grid";

.info {
  @include row-flex();
  @include lg(justify-content, center);

  .card {
    @include col();
    @include size(12);
  }
}

.card {
  background: var(--app-black-25);
  color: var(--green-700);

  p {
    text-align: center;
  }
}
</style>
