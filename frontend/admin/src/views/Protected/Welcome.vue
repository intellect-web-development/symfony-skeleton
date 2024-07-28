<template>
  <div>
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
.info {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
.card {
  background: #00000026;
  color: #5c75a4;
  p {
    text-align: center;
  }
}
</style>
