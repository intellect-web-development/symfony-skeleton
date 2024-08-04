<template>
  <div class="container container-center">
    <div class="info">
      <Card class="card">
        <template #title>
          {{ $t('views.public.welcome.title') }}
        </template>
        <template #content>
          <p>{{ $t('views.public.welcome.content') }}</p>
          <router-link to="/login">
            <Button :label="$t('views.public.welcome.link_button')" severity="secondary" />
          </router-link>
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
@import "../../../../smart-grid/smart-grid";

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
