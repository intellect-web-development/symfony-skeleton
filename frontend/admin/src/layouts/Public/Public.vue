<template>
  <div class="container-full">
    <Toolbar class="toolbar">
      <template #start>
        <Logo :to="{name: 'PublicWelcome'}" />
      </template>

      <template #center>
      </template>

      <template #end>
      </template>
    </Toolbar>
    <div class="workSpace">
      <RouterView />
    </div>
  </div>
  <Toast position="bottom-right" />
</template>

<script lang="ts">
import { RouterView } from 'vue-router'
import Logo from "@/components/Common/Logo/Logo.vue";

export default {
  components: {
    Logo,
    RouterView,
  },
  computed: {
  },
  data() {
    return {
      tPathMain: 'main.',
      tPath: 'layouts.public.',
    }
  },
}
</script>

<style lang="scss" scoped>
@import "@/assets/smart-grid";

.toolbar {
  @include row-flex();
}

.workSpace {
  height: 100%;
  @include row-flex();

  &__workZone {
    @include col();
    @include size(12);
  }
}
</style>
