<template>
  <div class="container container-center">
    <div class="info">
      <Card class="card">
        <template #title>
          {{ $t(tPath + 'title') }}
        </template>
        <template #content>
          <p>{{ $t(tPath + 'content') }}</p>
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
