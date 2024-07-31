<template>
  <div class="container container-center">
    <div class="info">
      <Card class="card">
        <template #title>
          {{ $t('views.public.welcome.title') }}
        </template>
        <template #content>
          <p>{{ $t('views.public.welcome.content') }}</p>
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
  background: #00000026;
  color: #5c75a4;
  p {
    text-align: center;
  }
}
</style>
