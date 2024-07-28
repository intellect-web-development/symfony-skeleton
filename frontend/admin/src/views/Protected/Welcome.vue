<template>
  <div class="main-container">
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

.main-container {
  @include wrapper();
  width: 100%;
}
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
