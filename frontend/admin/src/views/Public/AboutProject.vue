<template>
  <div class="height-100 vertical-center">
    <div class="welcome">
      <WelcomeItem>
        <template #icon>
          <mdicon style="color: var(--primary-500)" name="chart-timeline-variant-shimmer" />
        </template>
        <template #heading>
          <span>{{ $t('views.public.about-project.1.name') }}</span>
        </template>
        <p>
          {{ $t('views.public.about-project.1.description') }}
        </p>
      </WelcomeItem>

      <WelcomeItem>
        <template #icon>
          <mdicon style="color: var(--cyan-500)" name="recycle-variant" />
        </template>
        <template #heading>
          <span>{{ $t('views.public.about-project.2.name') }}</span>
        </template>
        <p>
          {{ $t('views.public.about-project.2.description') }}
        </p>
      </WelcomeItem>

      <WelcomeItem>
        <template #icon>
          <mdicon style="color: var(--yellow-500)" name="currency-btc" />
        </template>
        <template #heading>
          <span>{{ $t('views.public.about-project.3.name') }}</span>
        </template>
        <p>
          {{ $t('views.public.about-project.3.description') }}
        </p>
      </WelcomeItem>

      <WelcomeItem>
        <template #icon>
          <mdicon style="color: var(--indigo-600)" name="axis-arrow" />
        </template>
        <template #heading>
          <span>{{ $t('views.public.about-project.4.name') }}</span>
        </template>
        <p>
          {{ $t('views.public.about-project.4.description') }}
        </p>
      </WelcomeItem>
    </div>
  </div>
</template>

<script setup lang="ts">
import WelcomeItem from '../../components/WelcomeItem.vue'
</script>

<style lang="scss" scoped>
.welcome {
  padding-left: 1rem;
}
</style>