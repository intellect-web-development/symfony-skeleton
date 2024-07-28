<template>
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
</template>

<script lang="ts">
export default {
}
</script>

<style lang="scss" scoped>
@import "smart-grid/smart-grid";

.info {
  @include row-flex();
  @include md(justify-content, center);

  .card {
    @include col();
    @include size(3);
    @include size-md(5);
    @include size-xs(10);
  }
}

.card {
  background: #00000026;
  color: #5c75a4;
  p {
    text-align: center;
  }
}
</style>
