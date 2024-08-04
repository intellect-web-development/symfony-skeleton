<template>
  <div class="container-public">
    <Toolbar class="menubar">
      <template #start>
        <RouterLink :to="{name: 'PublicWelcome'}" class="logo-link">
          <div class="logo">
            <img alt="Skeleton logo" src="../../assets/img/favicon.ico" width="50" height="50" />
            <span class="project-name"> {{ $t('main.project-name') }}</span>
          </div>
        </RouterLink>
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
  <Toast position="bottom-right" group="br" />
</template>

<script lang="ts">
import { RouterView } from 'vue-router'

export default {
  components: {
    RouterView,
  },
  computed: {
  },
  data() {
    return {
      tPath: 'layouts.public.',
    }
  },
}
</script>

<style lang="scss" scoped>
.logo-link {
  text-decoration: none;
  font-size: 1rem;
  font-weight: bold;
}
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
.logo {
  .project-name {
    margin-left: 0.5rem;
  }
  margin: 0 0.5rem;
  display: flex;
  align-items: center;
}
.p-button.p-button-text {
  height: 100%;
}
</style>
