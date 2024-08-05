<template>
  <div class="container-public">
    <Toolbar class="menubar">
      <template #start>
        <Logo :to="{name: 'PublicWelcome'}" />
      </template>

      <template #center>
      </template>

      <template #end>
      </template>
    </Toolbar>
    <div class="main">
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
.container-public {
  margin: 0 auto;
  font-weight: normal;
  height: 100%;
  display: flex;
  flex-direction: column;

  .main {
    height: 100%;
    display: flex;
    flex-direction: column;
  }
}
.p-button.p-button-text {
  height: 100%;
}
</style>
