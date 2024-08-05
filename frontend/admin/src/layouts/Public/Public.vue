<template>
  <div class="layoutContainer">
    <Toolbar>
      <template #start>
        <Logo :to="{name: 'Welcome'}" />
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
.layoutContainer {
  margin: 0 auto;
  font-weight: normal;
  height: 100%;
  display: flex;
  flex-direction: column;

  .workSpace {
    height: 100%;
    display: flex;
    flex-direction: column;
  }
}
</style>
